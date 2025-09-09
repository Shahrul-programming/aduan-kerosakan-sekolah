<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TestController extends Controller
{
    public function testSession()
    {
        return response()->json([
            'session_id' => session()->getId(),
            'csrf_token' => csrf_token(),
            'session_driver' => config('session.driver'),
            'app_key' => config('app.key') ? 'SET' : 'NOT SET',
            'session_path' => config('session.path'),
            'session_domain' => config('session.domain'),
            'app_url' => config('app.url'),
        ]);
    }

    public function testCsrf(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'CSRF working!',
            'token' => $request->input('_token'),
        ]);
    }

    public function testAuth()
    {
        $users = User::all();
        $testUser = User::where('email', 'superadmin@demo.com')->first();

        return response()->json([
            'total_users' => $users->count(),
            'all_users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ];
            }),
            'test_user_exists' => $testUser ? 'YES' : 'NO',
            'test_user_data' => $testUser ? [
                'id' => $testUser->id,
                'name' => $testUser->name,
                'email' => $testUser->email,
                'role' => $testUser->role,
                'password_hash_length' => strlen($testUser->password),
                'password_starts_with' => substr($testUser->password, 0, 10),
            ] : null,
            'password_check_123' => $testUser ? Hash::check('password123', $testUser->password) : 'USER_NOT_FOUND',
            'password_check_normal' => $testUser ? Hash::check('password', $testUser->password) : 'USER_NOT_FOUND',
            'manual_login_attempt' => $this->attemptLogin('superadmin@demo.com', 'password123'),
        ]);
    }

    private function attemptLogin($email, $password)
    {
        try {
            $credentials = ['email' => $email, 'password' => $password];
            if (Auth::attempt($credentials)) {
                Auth::logout(); // logout immediately after test

                return 'SUCCESS';
            } else {
                return 'FAILED';
            }
        } catch (\Exception $e) {
            return 'ERROR: '.$e->getMessage();
        }
    }
}
