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
                $expectedDefaultEmail = $school->code.'@sekolah.admin';
                $school->login_password_hint = ($admin->email === $expectedDefaultEmail) ? 'password' : '(sudah ditetapkan oleh admin)';
            } else {
                // Fallback defaults if no admin exists
                $school->login_email = $school->code.'@sekolah.admin';
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
            // For simple create flow, only Nama & Kod diperlukan seperti kehendak baru.
            // Medan lain boleh dikemaskini kemudian di halaman edit sekolah.
        ]);

        // Cipta sekolah – hanya nama & kod diperlukan
        $school = new \App\Models\School;
        $school->name = $request->input('name');
        $school->code = $request->input('code');
        $school->address = $request->input('address', '');
        $school->principal_name = $request->input('principal_name', '');
        $school->principal_phone = $request->input('principal_phone', '');
        $school->hem_name = $request->input('hem_name', '');
        $school->hem_phone = $request->input('hem_phone', '');
        $school->save();

        return redirect()->route('schools.index')->with('success', 'Sekolah berjaya ditambah. Seterusnya, lantik Admin Sekolah melalui halaman Edit Sekolah.');
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
            'code' => 'required|unique:schools,code,'.$school->id,
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
        if (! $user->school_id) {
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
            $expectedDefaultEmail = $school->code.'@sekolah.admin';
            $password_hint = ($admin->email === $expectedDefaultEmail) ? 'password' : '(sudah ditetapkan oleh admin)';
        } else {
            $email = $school->code.'@sekolah.admin';
            $password_hint = 'password';
        }

        return response()->json([
            'email' => $email,
            'password_hint' => $password_hint,
            'school_code' => $school->code,
        ]);
    }

    /**
     * Lantik admin sekolah (Pilihan A: Super admin lantik terus)
     */
    public function assignAdmin(Request $request, $id)
    {
        // Pastikan hanya super_admin dibenarkan (middleware di route juga melindungi)
        if (! auth()->check() || auth()->user()->role !== 'super_admin') {
            abort(403);
        }

        $school = \App\Models\School::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|confirmed|min:6',
        ]);

        \DB::beginTransaction();
        try {
            // Nyah-lantik admin lama (jika ada) – polisi: satu admin per sekolah
            $prev = \App\Models\User::where('role', 'school_admin')->where('school_id', $school->id)->first();
            if ($prev) {
                $prev->role = 'guru'; // atau null mengikut polisi
                $prev->save();
            }

            // Cipta user baharu sebagai admin sekolah
            $user = new \App\Models\User;
            $user->name = $validated['name'];
            $user->position = $validated['position'] ?? null;
            $user->email = $validated['email'];
            $user->phone = $validated['phone'] ?? null;
            $user->password = bcrypt($validated['password']);
            $user->role = 'school_admin';
            $user->school_id = $school->id;
            $user->save();

            // Log aktiviti (tanpa kaitan aduan). Jangan isi complaint_id dengan school_id.
            \App\Http\Controllers\ActivityLogController::log(
                auth()->id(),
                'lantik admin sekolah: '.$user->email.' (school: '.$school->code.')',
                null
            );
            try {
                \Mail::to($user->email)->send(new \App\Mail\SchoolAdminCredentials($user, $validated['password'], $school));
            } catch (\Exception $e) {
                \Log::warning('Gagal hantar email credential admin sekolah: '.$e->getMessage());
            }

            \DB::commit();

            return back()->with('success', 'User berjaya dilantik sebagai Admin Sekolah.');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Gagal lantik admin sekolah', ['error' => $e->getMessage(), 'school_id' => $school->id]);

            return back()->withErrors(['general' => 'Gagal melantik admin sekolah.']);
        }
    }
}
