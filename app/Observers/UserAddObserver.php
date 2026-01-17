<?php

namespace App\Observers;

use App\Models\UserAdd;
use App\Models\User;

class UserAddObserver
{
    /**
     * Handle the UserAdd "created" event.
     */
    public function created(UserAdd $userAdd): void
    {
        User::where('id', $userAdd->user_id)->increment('adds_count');
        User::where('id', $userAdd->added_user_id)->increment('added_by_count');
    }

    /**
     * Handle the UserAdd "updated" event.
     */
    public function updated(UserAdd $userAdd): void
    {

    }

    /**
     * Handle the UserAdd "deleted" event.
     */
    public function deleted(UserAdd $userAdd): void
    {
        User::where('id', $userAdd->user_id)->decrement('adds_count');
        User::where('id', $userAdd->added_user_id)->decrement('added_by_count');
    }

    /**
     * Handle the UserAdd "restored" event.
     */
    public function restored(UserAdd $userAdd): void
    {
        //
    }

    /**
     * Handle the UserAdd "force deleted" event.
     */
    public function forceDeleted(UserAdd $userAdd): void
    {
        //
    }
}
