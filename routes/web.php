<?php

use App\Http\Controllers\AffiliatesController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('dashboard')->group(function () {
    Route::get('/list-affiliates', [AffiliatesController::class, 'create'])->name('dashboard.list-affiliates');
})->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
