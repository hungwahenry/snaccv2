<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\Snacc;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class SearchService
{
    /**
     * Search for snaccs based on query
     */
    public function searchSnaccs(
        string $query,
        ?int $universityId = null,
        string $sort = 'relevant',
        int $perPage = 10
    ): LengthAwarePaginator {
        $snaccsQuery = Snacc::with([
            'user.profile',
            'university',
            'images',
            'vibetags',
            'quotedSnacc.user.profile',
            'quotedSnacc.images'
        ])
        ->withCount(['comments', 'likes'])
        ->search($query)
        ->notDeleted();

        // Filter by university if provided (campus search)
        if ($universityId) {
            $snaccsQuery->forUniversity($universityId);
        }

        // Apply sorting
        $snaccsQuery = $this->applySorting($snaccsQuery, $sort);

        return $snaccsQuery->paginate($perPage);
    }

    /**
     * Search for users based on query
     */
    public function searchUsers(
        string $query,
        ?int $universityId = null,
        string $sort = 'relevant',
        int $perPage = 10
    ): LengthAwarePaginator {
        $usersQuery = Profile::with([
            'user',
            'university'
        ])
        ->search($query);

        // Filter by university if provided (campus search)
        if ($universityId) {
            $usersQuery->where('university_id', $universityId);
        }

        // Apply sorting
        if ($sort === 'popular') {
            $usersQuery->join('users', 'profiles.user_id', '=', 'users.id')
                      ->orderByDesc('users.cred_score')
                      ->orderByDesc('users.added_by_count')
                      ->select('profiles.*');
        } else {
            // Default: most recently created profiles first
            $usersQuery->latest();
        }

        return $usersQuery->paginate($perPage);
    }

    /**
     * Apply sorting to snacc query
     */
    protected function applySorting(Builder $query, string $sort): Builder
    {
        return match ($sort) {
            'trending' => $query->trending(),
            'latest' => $query->latest(),
            'popular' => $query->orderByDesc('likes_count')
                              ->orderByDesc('comments_count')
                              ->orderByDesc('views_count'),
            default => $query->orderByDesc('heat_score')
                            ->orderByDesc('created_at'),
        };
    }

    /**
     * Get trending search terms (placeholder for future implementation)
     */
    public function getTrendingSearches(int $limit = 5): array
    {
        // TODO: Implement tracking and retrieval of trending searches
        return [];
    }
}
