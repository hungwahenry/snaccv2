<?php

namespace App\Services;

use App\Models\CredTier;
use App\Models\CredTransaction;
use App\Models\ScoringRule;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CredService
{
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
            $user->refresh();
            $this->resetDailyCapIfNeeded($user);

            $amount = (int) ScoringRule::getValue("cred.action.{$action}", 0);

            if ($amount === 0) {
                return null;
            }

            $dailyCap = (int) ScoringRule::getValue('cred.limit.daily_cap', 150);

            if ($amount > 0) {
                $newDailyTotal = $user->daily_cred_earned + $amount;

                if ($newDailyTotal > $dailyCap) {
                    $amount = $dailyCap - $user->daily_cred_earned;

                    if ($amount <= 0) {
                        return null;
                    }
                }

                $user->increment('daily_cred_earned', $amount);
            }

            $user->increment('cred_score', $amount);
            $this->updateUserTier($user);

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
            $amount = (int) ScoringRule::getValue("cred.penalty.{$action}", 0);

            if ($amount >= 0) {
                throw new InvalidArgumentException("Action '{$action}' is not a deduction action");
            }

            $user->decrement('cred_score', abs($amount));
            $this->updateUserTier($user);

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
                return;
            }

            $yesterday = now()->subDay()->toDateString();

            if ($lastLogin === $yesterday) {
                $user->increment('login_streak');
            } elseif ($lastLogin !== null) {
                $user->update(['login_streak' => 1]);
            } else {
                $user->update(['login_streak' => 1]);
            }

            $user->update(['last_login_date' => $today]);

            $this->awardCred(
                user: $user,
                action: 'login_streak',
                description: "Login streak: {$user->login_streak} days"
            );
        });
    }

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

    protected function updateUserTier(User $user): void
    {
        $user->refresh();

        $tier = CredTier::getTierForScore($user->cred_score);

        if ($tier && $user->cred_tier_id !== $tier->id) {
            $user->update(['cred_tier_id' => $tier->id]);
        }
    }


}
