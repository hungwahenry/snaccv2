<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserAdd;
use App\Notifications\UserAdded;
use Illuminate\Support\Facades\Gate;

class UserAddService
{
    public function addUser(User $actor, User $target): array
    {
        // Validation logic
        if ($actor->id === $target->id) {
            throw new \InvalidArgumentException('Cannot add yourself');
        }

        $alreadyAdded = $actor->addedUsers()->where('added_user_id', $target->id)->exists();
        if ($alreadyAdded) {
            throw new \InvalidArgumentException('User already added');
        }

        UserAdd::firstOrCreate([
            'user_id' => $actor->id,
            'added_user_id' => $target->id
        ]);

        // Notify
        $target->notify(new UserAdded($actor));

        return [
            'success' => true,
            'is_added' => true,
            'added_by_count' => $target->fresh()->added_by_count
        ];
    }

    public function removeUser(User $actor, User $target): array
    {
        $userAdd = UserAdd::where('user_id', $actor->id)
                          ->where('added_user_id', $target->id)
                          ->firstOrFail();

        Gate::forUser($actor)->authorize('delete', $userAdd);

        $userAdd->delete();

        return [
            'success' => true,
            'is_added' => false,
            'added_by_count' => $target->fresh()->added_by_count
        ];
    }
}
