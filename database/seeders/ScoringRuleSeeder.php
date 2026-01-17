<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScoringRule;

class ScoringRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rules = [
            // Cred Actions
            ['key' => 'cred.action.post_created', 'value' => 1, 'category' => 'cred', 'description' => 'Points for creating a post'],
            ['key' => 'cred.action.like_received', 'value' => 2, 'category' => 'cred', 'description' => 'Points for receiving a like'],
            ['key' => 'cred.action.comment_received', 'value' => 3, 'category' => 'cred', 'description' => 'Points for receiving a comment'],
            ['key' => 'cred.action.quote_received', 'value' => 5, 'category' => 'cred', 'description' => 'Points for getting quoted'],
            ['key' => 'cred.action.reply_received', 'value' => 3, 'category' => 'cred', 'description' => 'Points for receiving a reply'],
            ['key' => 'cred.action.login_streak', 'value' => 5, 'category' => 'cred', 'description' => 'Points per day of login streak'],
            ['key' => 'cred.action.viral_bonus', 'value' => 100, 'category' => 'cred', 'description' => 'Bonus when post reaches 1000 heat'],
            
            // Cred Penalties
            ['key' => 'cred.penalty.post_deleted', 'value' => -5, 'category' => 'cred', 'description' => 'Penalty for deleting own post'],
            ['key' => 'cred.penalty.reported', 'value' => -50, 'category' => 'cred', 'description' => 'Penalty when report is confirmed'],
            ['key' => 'cred.penalty.warning', 'value' => -100, 'category' => 'cred', 'description' => 'Penalty for account warning'],
            ['key' => 'cred.penalty.spam', 'value' => -25, 'category' => 'cred', 'description' => 'Penalty for spamming'],
            
            // Cred Limits
            ['key' => 'cred.limit.daily_cap', 'value' => 150, 'category' => 'cred', 'description' => 'Maximum daily cred earned'],

            // Heat Weights
            ['key' => 'heat.weight.view', 'value' => 0.5, 'category' => 'heat_weight', 'description' => 'Heat points for a view'],
            ['key' => 'heat.weight.like', 'value' => 2, 'category' => 'heat_weight', 'description' => 'Heat points for a like'],
            ['key' => 'heat.weight.comment', 'value' => 5, 'category' => 'heat_weight', 'description' => 'Heat points for a comment'],
            ['key' => 'heat.weight.quote', 'value' => 8, 'category' => 'heat_weight', 'description' => 'Heat points for a quote'],
            ['key' => 'heat.weight.reply', 'value' => 3, 'category' => 'heat_weight', 'description' => 'Heat points for a reply'],

            // Heat Multipliers (Time in hours => Multiplier)
            ['key' => 'heat.multiplier.1h', 'value' => 2.0, 'category' => 'heat_multiplier', 'description' => 'Velocity multiplier: first 1 hour'],
            ['key' => 'heat.multiplier.3h', 'value' => 1.75, 'category' => 'heat_multiplier', 'description' => 'Velocity multiplier: first 3 hours'],
            ['key' => 'heat.multiplier.6h', 'value' => 1.5, 'category' => 'heat_multiplier', 'description' => 'Velocity multiplier: first 6 hours'],
            ['key' => 'heat.multiplier.12h', 'value' => 1.25, 'category' => 'heat_multiplier', 'description' => 'Velocity multiplier: first 12 hours'],
            ['key' => 'heat.multiplier.24h', 'value' => 1.0, 'category' => 'heat_multiplier', 'description' => 'Velocity multiplier: after 24 hours'],

            // Heat Decay (Time in hours => Factor)
            // Note: 0-24h is 1.0 (default)
            ['key' => 'heat.decay.24h', 'value' => 0.8, 'category' => 'heat_decay', 'description' => 'Decay factor after 24 hours'],
            ['key' => 'heat.decay.48h', 'value' => 0.5, 'category' => 'heat_decay', 'description' => 'Decay factor after 48 hours'],
            ['key' => 'heat.decay.60h', 'value' => 0.25, 'category' => 'heat_decay', 'description' => 'Decay factor after 60 hours'],
            ['key' => 'heat.decay.72h', 'value' => 0.1, 'category' => 'heat_decay', 'description' => 'Decay factor after 72 hours'],
            ['key' => 'heat.decay.96h', 'value' => 0.0, 'category' => 'heat_decay', 'description' => 'Decay factor after 96 hours'],
        ];

        foreach ($rules as $rule) {
            ScoringRule::updateOrCreate(
                ['key' => $rule['key']],
                $rule
            );
        }
    }
}
