<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Snacc;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
    ) {}

    public function show(Request $request, string $username)
    {
        $profile = Profile::where('username', $username)->firstOrFail();
        $user = $profile->user;

        // Authorize profile view
        Gate::authorize('view', $user);

        $isOwnProfile = Auth::id() === $user->id;
        $isAdded = Auth::check() ? $user->isAddedBy(Auth::user()) : false;

        $snaccs = Snacc::where('user_id', $user->id)
            ->with([
                'user.profile',
                'user.credTier',
                'university',
                'images',
                'vibetags',
                'quotedSnacc.user.profile',
                'quotedSnacc.images'
            ])
            ->withCount(['comments', 'likes'])
            ->notDeleted()
            ->latest()
            ->paginate(10);

        // Infinite Scroll support
        if ($request->ajax()) {
            $view = view('components.posts.feed-list', compact('snaccs'))->render();
            return response()->json([
                'html' => $view,
                'next_page_url' => $snaccs->nextPageUrl()
            ]);
        }

        return view('profile.show', compact('user', 'profile', 'isOwnProfile', 'isAdded', 'snaccs'));
    }
}

