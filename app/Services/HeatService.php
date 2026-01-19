<?php

namespace App\Services;

use App\Models\ScoringRule;
use App\Models\Snacc;
use App\Notifications\SnaccViral;
use Illuminate\Support\Facades\DB;

class HeatService
{
    public function __construct(
        protected CredService $credService
    ) {}

    /**
     * Calculate heat score for a snacc
     */
    public function calculateHeat(Snacc $snacc): int
    {
        $baseScore = $this->calculateBaseScore($snacc);
        $velocityMultiplier = $this->getVelocityMultiplier($snacc);
        $scoreWithVelocity = $baseScore * $velocityMultiplier;
        $decayFactor = $this->getDecayFactor($snacc);

        return (int) round($scoreWithVelocity * $decayFactor);
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

            if ($newHeat > $oldHeat && ($snacc->heat_peak_at === null || $newHeat > $oldHeat)) {
                $snacc->update(['heat_peak_at' => now()]);
            }

            // Check for viral bonus (1000+ heat)
            if ($newHeat >= 1000 && $oldHeat < 1000) {
                // Award Cred
                $this->credService->awardCred(
                    user: $snacc->user,
                    action: 'viral_bonus',
                    source: $snacc,
                    description: "Post went viral with {$newHeat} heat"
                );

                // Notify User
                $snacc->user->notify(new SnaccViral($snacc));
            }
        });
    }

    protected function calculateBaseScore(Snacc $snacc): float
    {
        $score = 0;

        $score += $snacc->views_count * ScoringRule::getValue('heat.weight.view', 0.5);
        $score += $snacc->likes_count * ScoringRule::getValue('heat.weight.like', 2.0);
        $score += $snacc->comments_count * ScoringRule::getValue('heat.weight.comment', 5.0);
        $score += $snacc->quotes_count * ScoringRule::getValue('heat.weight.quote', 8.0);

        $repliesCount = $snacc->comments()->sum('replies_count');
        $score += $repliesCount * ScoringRule::getValue('heat.weight.reply', 3.0);

        return $score;
    }

    protected function getVelocityMultiplier(Snacc $snacc): float
    {
        $hoursSinceCreation = $snacc->created_at->diffInHours(now());
        $checkpoints = [1, 3, 6, 12, 24];

        foreach ($checkpoints as $hours) {
            if ($hoursSinceCreation <= $hours) {
                return ScoringRule::getValue("heat.multiplier.{$hours}h", 1.0);
            }
        }

        return 1.0;
    }

    protected function getDecayFactor(Snacc $snacc): float
    {
        $hoursSinceCreation = $snacc->created_at->diffInHours(now());
        $checkpoints = [96, 72, 60, 48, 24];

        foreach ($checkpoints as $hours) {
            if ($hoursSinceCreation >= $hours) {
                return ScoringRule::getValue("heat.decay.{$hours}h", 0.0);
            }
        }

        return 1.0;
    }

    /**
     * Batch recalculate heat for recent snaccs
     */
    public function recalculateRecentHeat(): int
    {
        $count = 0;
        
        $recentSnaccs = Snacc::where('created_at', '>=', now()->subDays(5))
            ->where('is_deleted', false)
            ->get();

        foreach ($recentSnaccs as $snacc) {
            $this->updateHeat($snacc);
            $count++;
        }

        return $count;
    }

}
