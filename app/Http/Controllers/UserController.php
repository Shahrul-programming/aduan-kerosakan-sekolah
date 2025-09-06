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
        $schools = School::all();
        return view('users.create', compact('schools'));
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable',
            'role' => 'required|in:school_admin,guru',
            'school_id' => 'required|exists:schools,id',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'school_id' => $request->school_id,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('schools.index')->with('success', 'Pengguna berjaya ditambah');
    }
}
