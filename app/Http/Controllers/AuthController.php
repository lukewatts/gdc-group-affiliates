<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class AuthController extends Controller
{
    public function redirect() {
        return Socialite::driver('github')->redirect();
    }

    public function callback(): RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->intended('/dashboard');
        }

        $user = Socialite::driver('github')->user();

        if (!$user) {
            throw new InvalidStateException('Unable to authenticate with GitHub.');
        }

        // Check if user with email exists
        $existingUser = User::where('email', $user->getEmail())->first();
        if ($existingUser) {
            // Update user's GitHub details
            $existingUser->github_id = $user->getId();
            $existingUser->github_token = $user->token;
            $existingUser->github_refresh_token = $user->refreshToken;
            $existingUser->save();

            // Login the user
            auth()->login($existingUser, true);

            return redirect()->intended('/dashboard');
        }

        $newUser = User::create([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'email_verified_at' => now(),
            'github_id' => $user->getId(),
            'github_token' => $user->token,
            'github_refresh_token' => $user->refreshToken,
        ]);

        auth()->login($newUser, true);

        return redirect()->intended('/dashboard');
    }
}
