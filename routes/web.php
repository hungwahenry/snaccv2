<?php

use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\AppSettingsController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentLikeController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\GiphyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PrivacySettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileSettingsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
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
Route::get('/search', [SearchController::class, 'index'])->middleware(['auth', 'verified'])->name('search');

Route::middleware('auth')->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    // Settings Routes
    Route::prefix('settings')->group(function () {
        Route::redirect('/', '/settings/profile');
        
        Route::get('/profile', [ProfileSettingsController::class, 'edit'])->name('settings.profile');
        Route::patch('/profile', [ProfileSettingsController::class, 'update'])->name('settings.profile.update');
        
        Route::get('/account', [AccountSettingsController::class, 'edit'])->name('settings.account');
        Route::patch('/account/email', [AccountSettingsController::class, 'updateEmail'])->name('settings.account.email');
        Route::post('/account/export', [AccountSettingsController::class, 'export'])->name('settings.account.export');
        Route::delete('/account', [AccountSettingsController::class, 'destroy'])->name('settings.account.destroy');
        Route::get('/app', [AppSettingsController::class, 'edit'])->name('settings.app');
        Route::patch('/app', [AppSettingsController::class, 'update'])->name('settings.app.update');
        Route::get('/privacy', [PrivacySettingsController::class, 'edit'])->name('settings.privacy');
    });

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
    
    // Blocking System
    Route::post('/users/{user}/block', [BlockController::class, 'store'])->name('users.block');
    Route::delete('/users/{user}/unblock', [BlockController::class, 'destroy'])->name('users.unblock');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::patch('/notifications/settings', [NotificationController::class, 'updateSettings'])->name('notifications.settings.update');
});

// Public Profile
Route::get('/{username}', [ProfileController::class, 'show'])->middleware(['auth'])->name('profile.show');