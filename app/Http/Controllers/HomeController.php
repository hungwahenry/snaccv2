<?php

namespace App\Http\Controllers;

use App\Models\Snacc;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
    ) {}

    public function index(Request $request): View|\Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        $sort = $request->query('sort', 'trending');

        // Campus feed: strictly user's university
        $query = Snacc::with([
            'user.profile',
            'university',
            'images',
            'vibetags',
            'quotedSnacc.user.profile',
            'quotedSnacc.images'
        ])
        ->forUniversity($user->profile->university_id)
        ->notDeleted()
        ->withoutBlockedUsers();

        // Sorting logic
        if ($sort === 'trending') {
            $query->trending();
        } elseif ($sort === 'added') {
            // Only posts from users I've added
            $query->whereIn('user_id', $user->addedUsers()->pluck('users.id'))
                  ->latest();
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

        return view('home', compact('snaccs', 'sort'));
    }
}
