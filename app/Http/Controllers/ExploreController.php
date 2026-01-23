<?php

namespace App\Http\Controllers;

use App\Models\Snacc;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ExploreController extends Controller
{
    public function __construct(
    ) {}

    public function index(Request $request)
    {
        $sort = $request->query('sort', 'trending');

        // Global feed: all non-deleted snaccs with global visibility
        $query = Snacc::with([
            'user.profile',
            'university',
            'images',
            'vibetags',
            'quotedSnacc.user.profile',
            'quotedSnacc.images'
        ])
        ->notDeleted()
        ->global();

        // Sorting logic
        if ($sort === 'trending') {
            $query->trending();
        } elseif ($sort === 'added') {
            $user = $request->user();
            if ($user) {
                $query->whereIn('user_id', $user->addedUsers()->pluck('users.id'))
                      ->latest();
            } else {
                $query->whereRaw('0 = 1');
            }
        } else {
            $query->latest();
        }

        $snaccs = $query->paginate(10);

        // Infinite Scroll support
        if ($request->ajax()) {
            $view = view('components.posts.feed-list', compact('snaccs'))->render();
            return response()->json([
                'html' => $view,
                'next_page_url' => $snaccs->nextPageUrl()
            ]);
        }

        return view('explore', compact('snaccs', 'sort'));
    }
}
