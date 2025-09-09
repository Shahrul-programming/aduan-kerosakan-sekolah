<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // Papar borang tambah user
    public function create()
    {
        $user = auth()->user();
        $schools = [];
        if ($user && $user->role === 'super_admin') {
            $schools = School::all();
        }

        return view('users.create', compact('schools', 'user'));
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $user = auth()->user();

        // Determine allowed roles and school_id behaviour
        $allowedRoles = ['school_admin', 'guru'];
        if ($user && $user->role === 'school_admin') {
            // school_admin may create kontraktor and guru for their own school
            $allowedRoles[] = 'kontraktor';
        } elseif ($user && $user->role === 'super_admin') {
            // super admin may create any role (include kontraktor)
            $allowedRoles[] = 'kontraktor';
        }

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable',
            'role' => 'required|in:'.implode(',', $allowedRoles),
        ];

        // Password required when creating non-kontraktor OR when super_admin creates a kontraktor
        $passwordRequired = false;
        if (! $request->filled('role') || $request->role !== 'kontraktor') {
            $passwordRequired = true;
        } elseif ($request->role === 'kontraktor' && $user && $user->role === 'super_admin') {
            // super_admin may set password manually for kontraktor
            $passwordRequired = true;
        }

        if ($passwordRequired) {
            $rules['password'] = 'required|confirmed|min:6';
        }

        // Only super_admin must provide school_id; school_admin's created users inherit their school
        if ($user && $user->role === 'super_admin') {
            $rules['school_id'] = 'required|exists:schools,id';
        }

        $request->validate($rules);

        $schoolId = null;
        if ($user && $user->role === 'school_admin') {
            $schoolId = $user->school_id;
        } elseif ($request->filled('school_id')) {
            $schoolId = $request->school_id;
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'school_id' => $schoolId,
        ];

        // If role is kontraktor: branch by creator role
        if ($request->role === 'kontraktor') {
            // If creator is super_admin, allow manual password set
            if ($user && $user->role === 'super_admin') {
                $userData['password'] = Hash::make($request->password);
                User::create($userData);

                return redirect()->route('schools.index')->with('success', 'Pengguna (kontraktor) berjaya ditambah.');
            }

            // For school_admin (or others), create kontraktor user and send reset invite
            $cUser = User::firstOrNew(['email' => $request->email]);
            $cUser->fill($userData);
            if (! $cUser->exists) {
                $cUser->password = Hash::make(Str::random(24));
            }
            $cUser->save();

            $status = Password::sendResetLink(['email' => $request->email]);

            $flash = ['success' => 'Pengguna (kontraktor) berjaya ditambah.'];
            if ($status === Password::RESET_LINK_SENT) {
                $flash['notice'] = 'Emel jemputan telah dihantar kepada kontraktor untuk menetapkan kata laluan.';
            } else {
                $flash['warning'] = 'Kontraktor dicipta tetapi jemputan e-mel tidak berjaya dihantar.';
            }

            $redirectRoute = ($user && $user->role === 'super_admin') ? 'schools.index' : 'dashboard';

            return redirect()->route($redirectRoute)->with($flash);
        }

        // Other roles: create with provided password
        $userData['password'] = Hash::make($request->password);
        User::create($userData);

        $redirectRoute = ($user && $user->role === 'super_admin') ? 'schools.index' : 'dashboard';

        return redirect()->route($redirectRoute)->with('success', 'Pengguna berjaya ditambah');
    }
}
