<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $allowedRoles = ['school_admin','guru'];
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
            'role' => 'required|in:' . implode(',', $allowedRoles),
            'password' => 'required|confirmed|min:6',
        ];

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

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'school_id' => $schoolId,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('schools.index')->with('success', 'Pengguna berjaya ditambah');
    }
}
