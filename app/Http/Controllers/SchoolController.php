<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager-load admin relationship so view can display login info
        $schools = \App\Models\School::with('admin')->get();

        // For backward compatibility and ease in the view, attach a small helper
        // that indicates the login email and whether the password is the default.
        $schools->transform(function ($school) {
            $admin = $school->admin;
            if ($admin) {
                $school->login_email = $admin->email;

                // We only know the plaintext password in the case where the
                // system created the account with the default 'password'. For
                // other accounts we should not reveal or guess the password.
                // We detect default account by checking whether the email
                // matches the convention code@sekolah.admin and if so assume
                // the default password 'password' was used when created via UI.
                $expectedDefaultEmail = $school->code . '@sekolah.admin';
                $school->login_password_hint = ($admin->email === $expectedDefaultEmail) ? 'password' : '(sudah ditetapkan oleh admin)';
            } else {
                // Fallback defaults if no admin exists
                $school->login_email = $school->code . '@sekolah.admin';
                $school->login_password_hint = 'password';
            }
            return $school;
        });

        return view('schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('schools.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:schools',
            'address' => 'required',
            'principal_name' => 'required',
            'principal_phone' => 'required',
            'hem_name' => 'required',
            'hem_phone' => 'required',
            // optional admin fields will be validated conditionally
            'admin_email' => 'nullable|email|unique:users,email',
            'admin_password' => 'nullable|confirmed|min:6',
        ]);
        
        // Cipta sekolah
        $school = \App\Models\School::create($request->all());
        
        // Automatik cipta akaun admin sekolah
        if ($request->filled('admin_email') && $request->filled('admin_name') && $request->filled('admin_password')) {
            $user = new \App\Models\User();
            $user->name = $request->admin_name;
            $user->email = $request->admin_email;
            $user->password = bcrypt($request->admin_password);
            $user->role = 'school_admin';
            $user->school_id = $school->id;
            $user->save();
            $plaintextPassword = $request->admin_password;
            $loginInfo = 'Login: ' . $user->email . ' | Password: (yang anda tetapkan)';
        } else {
            $user = new \App\Models\User();
            $user->name = 'Admin ' . $request->name;
            $user->email = $request->code . '@sekolah.admin'; // Guna kod sekolah sebagai email
            $plaintextPassword = 'password';
            $user->password = bcrypt($plaintextPassword); // Password default
            $user->role = 'school_admin';
            $user->school_id = $school->id;
            $user->save();
            $loginInfo = 'Login: ' . $user->email . ' | Password: password';
        }

        // Hantar email credential (bergantung pada MAIL_MAILER di .env)
        try {
            \Mail::to($user->email)->send(new \App\Mail\SchoolAdminCredentials($user, $plaintextPassword, $school));
        } catch (\Exception $ex) {
            // Log and continue; do not fail the whole request because of mail issues
            \Log::error('Gagal hantar email credential admin sekolah', ['error' => $ex->getMessage(), 'school_id' => $school->id]);
        }
        
    return redirect()->route('schools.index')->with('success', 'Sekolah berjaya ditambah dan akaun admin sekolah telah dicipta. ' . $loginInfo);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $school = \App\Models\School::findOrFail($id);
        return view('schools.show', compact('school'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $school = \App\Models\School::findOrFail($id);
        return view('schools.edit', compact('school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $school = \App\Models\School::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:schools,code,' . $school->id,
            'address' => 'required',
            'principal_name' => 'required',
            'principal_phone' => 'required',
            'hem_name' => 'required',
            'hem_phone' => 'required',
        ]);
        $school->update($request->all());
        return redirect()->route('schools.index')->with('success', 'Sekolah berjaya dikemaskini');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $school = \App\Models\School::findOrFail($id);
        $school->delete();
        return redirect()->route('schools.index')->with('success', 'Sekolah berjaya dipadam');
    }

    /**
     * Papar QR code untuk sekolah admin yang login
     */
    public function qrCode()
    {
        $user = auth()->user();
        
        // Pastikan user ada school_id yang sah
        if (!$user->school_id) {
            abort(404, 'User tidak dikaitkan dengan sekolah manapun.');
        }
        
        // Cari sekolah berdasarkan ID
        $schoolObj = \App\Models\School::findOrFail($user->school_id);
        return view('schools.qr', compact('schoolObj'));
    }

    /**
     * Return login info (email + password hint) for a school as JSON.
     * This is intended for the super admin UI (AJAX) so we don't embed
     * sensitive info in the DOM for all users.
     */
    public function loginInfo($id)
    {
        $school = \App\Models\School::with('admin')->findOrFail($id);
        $admin = $school->admin;

        if ($admin) {
            $email = $admin->email;
            $expectedDefaultEmail = $school->code . '@sekolah.admin';
            $password_hint = ($admin->email === $expectedDefaultEmail) ? 'password' : '(sudah ditetapkan oleh admin)';
        } else {
            $email = $school->code . '@sekolah.admin';
            $password_hint = 'password';
        }

        return response()->json([
            'email' => $email,
            'password_hint' => $password_hint,
            'school_code' => $school->code,
        ]);
    }
}
