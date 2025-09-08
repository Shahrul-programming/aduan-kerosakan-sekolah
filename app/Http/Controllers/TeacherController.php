<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;

class TeacherController extends Controller
{
    // Papar borang daftar guru
    public function showRegisterForm($code)
    {
        $received = trim((string) $code);
        \Log::info('Kod sekolah diterima untuk daftar guru:', ['code' => $received]);
        // Case-insensitive and trimmed lookup to avoid accidental mismatch
        $school = School::whereRaw('LOWER(code) = ?', [strtolower($received)])->first();
        \Log::info('Hasil query sekolah:', ['school_id' => $school ? $school->id : null, 'school_code' => $school ? $school->code : null]);
        if (!$school) {
            abort(404, 'Kod sekolah tidak dijumpai: ' . $received);
        }
        return view('teachers.register', compact('school'));
    }

    // Proses pendaftaran guru
    public function register(Request $request, $code)
    {
        $school = School::where('code', $code)->firstOrFail();
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'phone' => 'required',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->phone = $request->phone;
        $user->role = 'teacher';
        $user->school_id = $school->id;
        $user->save();
        return redirect()->route('login')->with('success', 'Pendaftaran guru berjaya!');
    }
}
