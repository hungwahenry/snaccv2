<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // OTP Authentication Routes
    Route::get('auth', [OtpController::class, 'showEmailForm'])
        ->name('auth');

    // Keep these for backward compatibility
    Route::get('login', [OtpController::class, 'showEmailForm'])
        ->name('login');

    Route::get('register', [OtpController::class, 'showEmailForm'])
        ->name('register');

    Route::post('auth/send', [OtpController::class, 'sendOtp'])
        ->name('auth.send');

    Route::get('auth/verify', [OtpController::class, 'showVerifyForm'])
        ->name('auth.verify.show');

    Route::post('auth/verify', [OtpController::class, 'verifyOtp'])
        ->name('auth.verify');

    Route::post('auth/resend', [OtpController::class, 'resendOtp'])
        ->name('auth.resend');
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
