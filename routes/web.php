<?php

use App\Http\Controllers\GiphyController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SnaccController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/snaccs', [SnaccController::class, 'store'])->name('snaccs.store');
    Route::delete('/snaccs/{snacc}', [SnaccController::class, 'destroy'])->name('snaccs.destroy');

    Route::get('/giphy/trending', [GiphyController::class, 'trending'])->name('giphy.trending');
    Route::get('/giphy/search', [GiphyController::class, 'search'])->name('giphy.search');
});

require __DIR__.'/auth.php';
