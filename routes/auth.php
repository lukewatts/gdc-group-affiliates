<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
});

Route::get('/auth/callback', function () {
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
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
