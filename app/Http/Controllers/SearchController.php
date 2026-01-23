<?php

namespace App\Http\Controllers;

use App\Models\Vibetag;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __construct(
        protected SearchService $searchService
    ) {}

    public function index(Request $request): View|\Illuminate\Http\JsonResponse
    {
        $query = $request->query('q', '');
        $type = $request->query('type', 'posts'); // posts or users
        $scope = $request->query('scope', 'global'); // global or campus
        $sort = $request->query('sort', 'relevant');

        // Validate query
        if (empty(trim($query))) {
            // Get top 20 trending vibetags
            $trendingVibetags = Vibetag::orderByDesc('usage_count')
                ->take(20)
                ->get();

            return view('search.index', [
                'query' => '',
                'type' => $type,
                'scope' => $scope,
                'sort' => $sort,
                'results' => collect(),
                'hasResults' => false,
                'trendingVibetags' => $trendingVibetags,
            ]);
        }

        $user = $request->user();
        $universityId = ($scope === 'campus' && $user) ? $user->profile->university_id : null;

        // Perform search based on type
        if ($type === 'users') {
            $results = $this->searchService->searchUsers(
                query: $query,
                universityId: $universityId,
                sort: $sort
            );
        } else {
            $results = $this->searchService->searchSnaccs(
                query: $query,
                universityId: $universityId,
                sort: $sort
            );
        }

        // Infinite Scroll support
        if ($request->ajax()) {
            if ($type === 'users') {
                $view = view('components.search.users-list', compact('results'))->render();
            } else {
                $view = view('components.posts.feed-list', ['snaccs' => $results])->render();
            }
            
            return response()->json([
                'html' => $view,
                'next_page_url' => $results->nextPageUrl()
            ]);
        }

        return view('search.index', [
            'query' => $query,
            'type' => $type,
            'scope' => $scope,
            'sort' => $sort,
            'results' => $results,
            'hasResults' => $results->total() > 0,
        ]);
    }
}
