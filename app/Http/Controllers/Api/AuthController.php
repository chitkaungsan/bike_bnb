<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|in:renter,owner',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('api-token')->accessToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
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

   
public function uploadAvatar(Request $request)
{
    $request->validate([
        'image' => 'required|string', // now expecting base64 string
    ]);

    try {
        // Get base64 string from request
        $base64Image = $request->input('image');

        // Check if string contains 'data:image/...;base64,' prefix
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
            $extension = strtolower($type[1]); // jpg, png, etc.
        } else {
            return response()->json(['error' => 'Invalid image format'], 422);
        }

        // Decode base64 to binary
        $imageData = base64_decode($base64Image);

        if ($imageData === false) {
            return response()->json(['error' => 'Base64 decode failed'], 422);
        }

        // Generate random filename
        $fileName = 'images/' . Str::random(10) . '.' . $extension;

        // Save to S3
        Storage::disk('s3')->put($fileName, $imageData, 'public');

        // Check if file exists
        if (Storage::disk('s3')->exists($fileName)) {
            return response()->json([
                'success' => true,
                'url' => Storage::disk('s3')->url($fileName)
            ]);
        } else {
            return response()->json(['error' => 'Upload failed, file not found on S3'], 500);
        }

    } catch (\Exception $e) {
        return response()->json(['error' => 'Upload exception: ' . $e->getMessage()], 500);
    }
}
}
