<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SocialLoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function callback($provider)
   {
        $frontendUrl = env('VITE_WEB_URL');
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(Str::random(16)),
            ]
        );

        $token = $user->createToken('Personal Access Token')->accessToken;

        // return response()->json([
        //     'token' => $token,
        //     'user' => $user
        // ]);
          return redirect("$frontendUrl/auth/google/callback?token=$token");
    }
}