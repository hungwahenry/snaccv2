<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserAdd;
use Illuminate\Auth\Access\Response;

class UserAddPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserAdd $userAdd): bool
    {
        return $user->id === $userAdd->user_id || $user->id === $userAdd->added_user_id;
    }

    /**
     * Determine whether the user can create a user add (add another user).
     * Note: This requires a target user parameter in the controller.
     */
    public function create(User $user): bool
    {
        // Basic check - user must have a profile
        return $user->profile !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserAdd $userAdd): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserAdd $userAdd): bool
    {
        // Can only delete your own adds
        return $user->id === $userAdd->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserAdd $userAdd): bool
    {
        return $user->id === $userAdd->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserAdd $userAdd): bool
    {
        return $user->id === $userAdd->user_id;
    }
}
