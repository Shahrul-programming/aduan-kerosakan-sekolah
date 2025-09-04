<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DebugController extends Controller
{
    public function testDomain(Request $request)
    {
        return response()->json([
            'url' => $request->url(),
            'domain' => $request->getHost(),
            'session_id' => session()->getId(),
            'csrf_token' => csrf_token(),
            'cookies' => $request->cookies->all(),
            'session_data' => session()->all(),
            'auth_check' => Auth::check(),
            'user' => Auth::user(),
        ]);
    }

    public function testLogin(Request $request)
    {
        $user = User::where('email', 'superadmin@demo.com')->first();
        
        if ($user && Hash::check('password123', $user->password)) {
            Auth::login($user);
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'user' => Auth::user(),
                'session_id' => session()->getId(),
                'redirect_url' => route('dashboard')
            ]);
        }
        
        return response()->json([
            'status' => 'failed',
            'message' => 'Login failed',
            'user_exists' => $user ? 'yes' : 'no',
            'password_check' => $user ? Hash::check('password123', $user->password) : false
        ]);
    }
}
