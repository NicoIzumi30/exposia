<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| These routes handle user authentication including registration, login,
| email verification, and logout for the UMKM platform.
|
*/

// Guest Routes (tidak boleh sudah login)
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::middleware('guest')->group(function () {
    // Registration Routes
    Route::post('/register', [AuthController::class, 'register']);
    // Login Routes
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes (harus sudah login)
Route::middleware('auth')->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Email Verification Routes
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

// AJAX Routes untuk form validation
Route::post('/check-business-url', [AuthController::class, 'checkBusinessUrl'])
    ->name('check.business.url')
    ->middleware('throttle:60,1');