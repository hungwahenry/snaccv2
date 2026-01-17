<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any profiles.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the profile.
     */
    public function view(?User $currentUser, User $targetUser): bool
    {
        // Profiles are viewable by authenticated users only
        return $currentUser !== null;
    }
}

