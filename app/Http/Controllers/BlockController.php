<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    /**
     * Block a user.
     */
    public function store(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->id === $user->id) {
            return back()->withErrors(['block' => 'you cannot block yourself.']);
        }

        if (!$request->user()->hasBlocked($user)) {
            $request->user()->blockedUsers()->attach($user->id);
            
            // Also remove any "add" relationship if exists (force unfollow)
            $request->user()->addedUsers()->detach($user->id);
            $user->addedUsers()->detach($request->user()->id);
        }

        return back()->with('success', 'user blocked.');
    }

    /**
     * Unblock a user.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        $request->user()->blockedUsers()->detach($user->id);

        return back()->with('success', 'user unblocked.');
    }
}
