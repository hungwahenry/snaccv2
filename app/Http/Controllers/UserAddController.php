<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAdd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserAddController extends Controller
{
    public function store(Request $request, User $user)
    {
        // Authorize basic create permission
        Gate::authorize('create', UserAdd::class);
        
        // Additional validation: cannot add yourself and must not already be added
        if ($request->user()->id === $user->id) {
            abort(403, 'Cannot add yourself');
        }

        $alreadyAdded = $request->user()->addedUsers()->where('added_user_id', $user->id)->exists();
        if ($alreadyAdded) {
            abort(403, 'User already added');
        }

        UserAdd::firstOrCreate([
            'user_id' => $request->user()->id,
            'added_user_id' => $user->id
        ]);

        return back();
    }

    public function destroy(Request $request, User $user)
    {
        $userAdd = UserAdd::where('user_id', $request->user()->id)
                          ->where('added_user_id', $user->id)
                          ->firstOrFail();

        Gate::authorize('delete', $userAdd);

        $userAdd->delete();

        return back();
    }
}

