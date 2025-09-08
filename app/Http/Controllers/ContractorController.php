<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contractor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ContractorController extends Controller
{
    /**
     * Show form to create a new contractor (school_admin only)
     */
    public function create()
    {
        // Only school_admin should see this; route middleware enforces role
        return view('contractors.create');
    }

    /**
     * Super-admin: list contractors and their assigned schools
     */
    public function manageIndex()
    {
        $contractors = Contractor::with('schools')->paginate(20);
        return view('contractors.manage.index', compact('contractors'));
    }

    public function manageCreate()
    {
        $schools = \App\Models\School::all();
        return view('contractors.manage.create', compact('schools'));
    }

    public function manageStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:contractors,email',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
            'schools' => 'nullable|array',
            'schools.*' => 'exists:schools,id',
        ]);

    $data = $request->only(['name','company_name','phone','email','address']);
    $data['phone'] = $data['phone'] ?? '';
    $contractor = Contractor::create($data);
        

        if ($request->filled('schools')) {
            $contractor->schools()->sync($request->schools);
        }

        // Optionally create user record for contractor without sending invite
        if ($contractor->email) {
            $u = User::firstOrNew(['email' => $contractor->email]);
            $u->name = $u->name ?: $contractor->name;
            $u->role = 'kontraktor';
            $u->school_id = $request->schools ? $request->schools[0] : null;
            if (!$u->exists) {
                $u->password = Hash::make(Str::random(24));
                $u->save();
            }
            // Link contractor to the created/updated user
            $contractor->user_id = $u->id;
            $contractor->save();
        }

        return redirect()->route('contractors.manage.index')->with('success', 'Kontraktor berjaya ditambah.');
    }

    public function manageEdit(Contractor $contractor)
    {
        $schools = \App\Models\School::all();
        $selected = $contractor->schools()->pluck('schools.id')->toArray();
        return view('contractors.manage.edit', compact('contractor','schools','selected'));
    }

    public function manageUpdate(Request $request, Contractor $contractor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
            'schools' => 'nullable|array',
            'schools.*' => 'exists:schools,id',
        ]);

        $contractor->update($request->only(['name','company_name','phone','email','address']));
        $contractor->schools()->sync($request->schools ?? []);

        return redirect()->route('contractors.manage.index')->with('success', 'Kontraktor dikemaskini.');
    }

    public function manageDestroy(Contractor $contractor)
    {
        $contractor->schools()->detach();
        $contractor->delete();
        return redirect()->route('contractors.manage.index')->with('success', 'Kontraktor dipadam.');
    }

    /**
     * Store a newly created contractor tied to the admin's school
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:1000',
        ]);

        $schoolId = auth()->user()->school_id;
        if (empty($schoolId)) {
            return back()->withErrors(['school' => 'Akaun admin sekolah tidak dikaitkan dengan mana-mana sekolah.']);
        }

    $data = $request->only(['name','company_name','phone','email','address']);
    $data['phone'] = $data['phone'] ?? '';
    $data['school_id'] = $schoolId;

    $contractor = Contractor::create($data);

        // Log activity if ActivityLogController exists
        if (class_exists('\App\Http\Controllers\ActivityLogController')) {
            // Don't store contractor id in complaint_id foreign key -- pass null
            \App\Http\Controllers\ActivityLogController::log(auth()->id(), 'create kontraktor: ' . $contractor->id, null);
        }

        $messages = ['success' => 'Kontraktor berjaya didaftar.'];

        // If an email was provided, create (or update) a User and send password reset link so
        // the contractor can set their password securely (option B requested).
        if (!empty($contractor->email)) {
            $email = $contractor->email;

            $user = User::firstOrNew(['email' => $email]);
            $user->name = $user->name ?: $contractor->name;
            $user->role = 'kontraktor';
            $user->school_id = $schoolId;

            // If user is newly created (no id yet), set a random temporary password (will be reset by user).
            if (!$user->exists) {
                $user->password = Hash::make(Str::random(24));
            }

            $user->save();

            // Link contractor record to the created/updated user
            $contractor->user_id = $user->id;
            $contractor->save();
            // Attempt to send password reset link. The Password broker returns a status string.
            $status = Password::sendResetLink(['email' => $email]);

            if ($status === Password::RESET_LINK_SENT) {
                $messages['notice'] = 'Emel jemputan telah dihantar kepada kontraktor untuk menetapkan kata laluan.';
            } else {
                // Keep success but add a warning if mail failed.
                $messages['warning'] = 'Kontraktor dicipta tetapi jemputan e-mel tidak berjaya dihantar.';
            }
        }

        return redirect()->route('complaints.index')->with($messages);
    }
}
