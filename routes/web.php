<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentLikeController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\GiphyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileSettingsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SnaccController;
use App\Http\Controllers\SnaccLikeController;
use App\Http\Controllers\SnaccViewController;
use App\Http\Controllers\UserAddController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';

Route::get('/home', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');
Route::get('/explore', [ExploreController::class, 'index'])->middleware(['auth', 'verified'])->name('explore');

Route::middleware('auth')->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    Route::get('/profile', [ProfileSettingsController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileSettingsController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileSettingsController::class, 'destroy'])->name('profile.destroy');

    Route::get('/snaccs/{snacc}', [SnaccViewController::class, 'show'])->name('snaccs.show');
    Route::post('/snaccs', [SnaccController::class, 'store'])->name('snaccs.store');
    Route::delete('/snaccs/{snacc}', [SnaccController::class, 'destroy'])->name('snaccs.destroy');
    Route::post('/snaccs/{snacc}/like', [SnaccLikeController::class, 'toggle'])->name('snaccs.like.toggle');

    Route::post('/snaccs/{snacc}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/snaccs/{snacc}/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::get('/comments/{comment}/replies', [CommentController::class, 'replies'])->name('comments.replies');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/like', [CommentLikeController::class, 'toggle'])->name('comments.like.toggle');

    Route::get('/giphy/trending', [GiphyController::class, 'trending'])->name('giphy.trending');
    Route::get('/giphy/search', [GiphyController::class, 'search'])->name('giphy.search');

    Route::get('/reports/categories/{type}', [ReportController::class, 'categories'])->name('reports.categories');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');

    // User Add System
    Route::post('/users/{user}/add', [UserAddController::class, 'store'])->name('users.add');
    Route::delete('/users/{user}/remove', [UserAddController::class, 'destroy'])->name('users.remove');
});

// Public Profile
Route::get('/{username}', [ProfileController::class, 'show'])->middleware(['auth'])->name('profile.show');