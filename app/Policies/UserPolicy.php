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

    public function view(?User $currentUser, User $targetUser): bool
    {
        // Public profiles are viewable by everyone, BUT we must check blocks if authenticated
        if ($currentUser) {
            // If I am blocked by them OR I blocked them -> Deny
            if ($currentUser->isBlockedBy($targetUser) || $currentUser->hasBlocked($targetUser)) {
                return false;
            }
        }

        return true;
    }
}

