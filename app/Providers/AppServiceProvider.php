<?php


namespace App\Providers;

use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Snacc;
use App\Models\SnaccLike;
use App\Models\UserAdd;
use App\Observers\CommentLikeObserver;
use App\Observers\CommentObserver;
use App\Observers\SnaccLikeObserver;
use App\Observers\SnaccObserver;
use App\Observers\UserAddObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Snacc::observe(SnaccObserver::class);
        SnaccLike::observe(SnaccLikeObserver::class);
        Comment::observe(CommentObserver::class);
        CommentLike::observe(CommentLikeObserver::class);
        UserAdd::observe(UserAddObserver::class);
    }
}
