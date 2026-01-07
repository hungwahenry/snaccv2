<?php

namespace App\Policies;

use App\Models\Snacc;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SnaccPolicy
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
    public function view(?User $user, Snacc $snacc): bool
    {
        // Global snaccs can be viewed by anyone (including guests)
        if ($snacc->visibility === 'global') {
            return true;
        }

        // Campus snaccs require authentication and same university
        if ($snacc->visibility === 'campus' && $user) {
            return $user->profile->university_id === $snacc->university_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->profile !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Snacc $snacc): bool
    {
        return $user->id === $snacc->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Snacc $snacc): bool
    {
        return $user->id === $snacc->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Snacc $snacc): bool
    {
        return $user->id === $snacc->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Snacc $snacc): bool
    {
        return $user->id === $snacc->user_id;
    }
}
