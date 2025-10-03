<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email'=> 'required|email|unique:users',
            'password'=> 'required|string|min:6|confirmed',
            'role'=> 'required|string|in:renter,owner',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'role'=> $request->role,
            'password'=> Hash::make($request->password),
        ]);

        $token = $user->createToken('api-token')->accessToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email'=> 'required|email',
            'password'=> 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api-token')->accessToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    public function dashboard(Request $request)
    {
        return view('dashboard');
    }   
}
