<?php

namespace App\Services;

use App\Models\Snacc;
use Illuminate\Support\Facades\DB;

class HeatService
{
    // Action weights for heat calculation
    const ACTION_WEIGHTS = [
        'view' => 0.5,
        'like' => 2,
        'comment' => 5,
        'quote' => 8,
        'reply' => 3,
    ];

    // Time decay percentages (hours since post creation)
    const TIME_DECAY = [
        0 => 1.0,    // 0-24 hours: 100%
        24 => 0.8,   // 24-48 hours: 80%
        48 => 0.5,   // 48-60 hours: 50%
        60 => 0.25,  // 60-72 hours: 25%
        72 => 0.1,   // 72-96 hours: 10%
        96 => 0.0,   // 96+ hours: 0%
    ];

    // Velocity multipliers (engagement within first N hours)
    const VELOCITY_MULTIPLIERS = [
        1 => 2.0,   // First hour: 2x
        3 => 1.75,  // First 3 hours: 1.75x
        6 => 1.5,   // First 6 hours: 1.5x
        12 => 1.25, // First 12 hours: 1.25x
        24 => 1.0,  // After 24 hours: 1x
    ];

    /**
     * Calculate heat score for a snacc
     */
    public function calculateHeat(Snacc $snacc): int
    {
        // Base engagement score
        $baseScore = $this->calculateBaseScore($snacc);

        // Apply velocity multiplier for early engagement
        $velocityMultiplier = $this->getVelocityMultiplier($snacc);
        $scoreWithVelocity = $baseScore * $velocityMultiplier;

        // Apply time decay
        $decayFactor = $this->getDecayFactor($snacc);
        $finalScore = $scoreWithVelocity * $decayFactor;

        return (int) round($finalScore);
    }

    /**
     * Update heat score for a snacc and track peak
     */
    public function updateHeat(Snacc $snacc): void
    {
        DB::transaction(function () use ($snacc) {
            $newHeat = $this->calculateHeat($snacc);
            $oldHeat = $snacc->heat_score;

            $snacc->update([
                'heat_score' => $newHeat,
                'heat_calculated_at' => now(),
            ]);

            // Track peak heat
            if ($newHeat > $oldHeat && ($snacc->heat_peak_at === null || $newHeat > $oldHeat)) {
                $snacc->update(['heat_peak_at' => now()]);
            }
        });
    }

    /**
     * Calculate base score from engagement counts
     */
    protected function calculateBaseScore(Snacc $snacc): float
    {
        $score = 0;

        $score += $snacc->views_count * self::ACTION_WEIGHTS['view'];
        $score += $snacc->likes_count * self::ACTION_WEIGHTS['like'];
        $score += $snacc->comments_count * self::ACTION_WEIGHTS['comment'];
        $score += $snacc->quotes_count * self::ACTION_WEIGHTS['quote'];

        // Add replies count (sum of all comment replies)
        $repliesCount = $snacc->comments()->sum('replies_count');
        $score += $repliesCount * self::ACTION_WEIGHTS['reply'];

        return $score;
    }

    /**
     * Get velocity multiplier based on how quickly engagement happened
     */
    protected function getVelocityMultiplier(Snacc $snacc): float
    {
        $hoursSinceCreation = $snacc->created_at->diffInHours(now());

        // Find appropriate multiplier based on age
        foreach (self::VELOCITY_MULTIPLIERS as $hours => $multiplier) {
            if ($hoursSinceCreation <= $hours) {
                return $multiplier;
            }
        }

        return 1.0;
    }

    /**
     * Get time decay factor based on post age
     */
    protected function getDecayFactor(Snacc $snacc): float
    {
        $hoursSinceCreation = $snacc->created_at->diffInHours(now());

        // Find appropriate decay factor based on age
        $decayFactor = 0.0;
        foreach (self::TIME_DECAY as $hours => $factor) {
            if ($hoursSinceCreation >= $hours) {
                $decayFactor = $factor;
            }
        }

        return $decayFactor;
    }

    /**
     * Get heat badge emoji based on heat score
     */
    public function getHeatBadge(int $heatScore): ?string
    {
        return match(true) {
            $heatScore >= 1000 => '💥', // Explosion
            $heatScore >= 500 => '🔥🔥', // Double fire
            $heatScore >= 100 => '🔥', // Single fire
            default => null,
        };
    }

    /**
     * Get trending snaccs for a university (campus-scoped)
     */
    public function getTrendingForUniversity(int $universityId, int $limit = 10)
    {
        return Snacc::where('university_id', $universityId)
            ->where('is_deleted', false)
            ->where('heat_score', '>', 0)
            ->where('created_at', '>=', now()->subDays(4)) // Only last 4 days
            ->orderByDesc('heat_score')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->with(['user.profile', 'images', 'university', 'quotedSnacc.user.profile'])
            ->get();
    }

    /**
     * Get global trending snaccs
     */
    public function getTrendingGlobal(int $limit = 10)
    {
        return Snacc::where('visibility', 'global')
            ->where('is_deleted', false)
            ->where('heat_score', '>', 0)
            ->where('created_at', '>=', now()->subDays(4))
            ->orderByDesc('heat_score')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->with(['user.profile', 'images', 'university', 'quotedSnacc.user.profile'])
            ->get();
    }

    /**
     * Batch recalculate heat for recent snaccs
     * This should be run by a scheduled job every hour
     */
    public function recalculateRecentHeat(): int
    {
        $count = 0;

        // Only recalculate for snaccs from last 5 days (heat becomes 0 after 96 hours anyway)
        $recentSnaccs = Snacc::where('created_at', '>=', now()->subDays(5))
            ->where('is_deleted', false)
            ->get();

        foreach ($recentSnaccs as $snacc) {
            $this->updateHeat($snacc);
            $count++;
        }

        return $count;
    }

    /**
     * Increment view count for a snacc
     */
    public function incrementView(Snacc $snacc): void
    {
        $snacc->increment('views_count');
    }
}
