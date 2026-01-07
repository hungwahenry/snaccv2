<?php

namespace App\Services;

use App\Models\CredTier;
use App\Models\CredTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CredService
{
    // Daily cred earning cap
    const DAILY_CAP = 150;

    // Cred amounts for different actions
    const CRED_AMOUNTS = [
        'post_created' => 1,
        'like_received' => 2,
        'comment_received' => 3,
        'quote_received' => 5,
        'reply_received' => 3,
        'login_streak' => 5, // per day
        'viral_bonus' => 100, // when heat reaches 1000+
        'post_deleted' => -5,
        'reported' => -50,
        'warning' => -100,
        'spam' => -25,
    ];

    /**
     * Award cred to a user with daily cap enforcement
     */
    public function awardCred(
        User $user,
        string $action,
        $source = null,
        ?string $description = null
    ): ?CredTransaction {
        return DB::transaction(function () use ($user, $action, $source, $description) {
            // Refresh user to get latest data
            $user->refresh();

            // Reset daily counter if it's a new day
            $this->resetDailyCapIfNeeded($user);

            // Get cred amount for this action
            $amount = self::CRED_AMOUNTS[$action] ?? 0;

            if ($amount === 0) {
                return null;
            }

            // For positive amounts, check daily cap
            if ($amount > 0) {
                $newDailyTotal = $user->daily_cred_earned + $amount;

                if ($newDailyTotal > self::DAILY_CAP) {
                    // Cap reached, award only what's left
                    $amount = self::DAILY_CAP - $user->daily_cred_earned;

                    if ($amount <= 0) {
                        // Already at cap, don't award anything
                        return null;
                    }
                }

                $user->increment('daily_cred_earned', $amount);
            }

            // Update user's total cred score
            $user->increment('cred_score', $amount);

            // Update user's tier based on new cred score
            $this->updateUserTier($user);

            // Create transaction record
            return CredTransaction::create([
                'user_id' => $user->id,
                'action' => $action,
                'amount' => $amount,
                'description' => $description,
                'source_type' => $source ? get_class($source) : null,
                'source_id' => $source?->id,
            ]);
        });
    }

    /**
     * Deduct cred from a user (no daily cap on negative amounts)
     */
    public function deductCred(
        User $user,
        string $action,
        $source = null,
        ?string $description = null
    ): CredTransaction {
        return DB::transaction(function () use ($user, $action, $source, $description) {
            $amount = self::CRED_AMOUNTS[$action] ?? 0;

            if ($amount >= 0) {
                throw new \InvalidArgumentException("Action '{$action}' is not a deduction action");
            }

            // Update user's total cred score (amount is already negative)
            $user->decrement('cred_score', abs($amount));

            // Update user's tier based on new cred score
            $this->updateUserTier($user);

            // Create transaction record
            return CredTransaction::create([
                'user_id' => $user->id,
                'action' => $action,
                'amount' => $amount,
                'description' => $description,
                'source_type' => $source ? get_class($source) : null,
                'source_id' => $source?->id,
            ]);
        });
    }

    /**
     * Update login streak and award streak cred
     */
    public function updateLoginStreak(User $user): void
    {
        DB::transaction(function () use ($user) {
            $today = now()->toDateString();
            $lastLogin = $user->last_login_date?->toDateString();

            if ($lastLogin === $today) {
                // Already logged in today, do nothing
                return;
            }

            $yesterday = now()->subDay()->toDateString();

            if ($lastLogin === $yesterday) {
                // Consecutive day, increment streak
                $user->increment('login_streak');
            } elseif ($lastLogin !== null) {
                // Streak broken, reset to 1
                $user->update(['login_streak' => 1]);
            } else {
                // First login ever
                $user->update(['login_streak' => 1]);
            }

            // Update last login date
            $user->update(['last_login_date' => $today]);

            // Award streak cred
            $this->awardCred(
                user: $user,
                action: 'login_streak',
                description: "Login streak: {$user->login_streak} days"
            );
        });
    }

    /**
     * Reset daily cap if it's a new day
     */
    protected function resetDailyCapIfNeeded(User $user): void
    {
        $today = now()->toDateString();
        $resetDate = $user->daily_cred_reset_date?->toDateString();

        if ($resetDate !== $today) {
            $user->update([
                'daily_cred_earned' => 0,
                'daily_cred_reset_date' => $today,
            ]);
        }
    }

    /**
     * Update user's tier based on their current cred score
     */
    protected function updateUserTier(User $user): void
    {
        $user->refresh();

        $tier = CredTier::getTierForScore($user->cred_score);

        if ($tier && $user->cred_tier_id !== $tier->id) {
            $user->update(['cred_tier_id' => $tier->id]);
        }
    }

    /**
     * Get user's remaining daily cred allowance
     */
    public function getRemainingDailyAllowance(User $user): int
    {
        $this->resetDailyCapIfNeeded($user);
        return max(0, self::DAILY_CAP - $user->daily_cred_earned);
    }
}
