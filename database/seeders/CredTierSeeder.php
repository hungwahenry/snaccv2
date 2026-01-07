<?php

namespace Database\Seeders;

use App\Models\CredTier;
use Illuminate\Database\Seeder;

class CredTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiers = [
            [
                'name' => 'newbie',
                'slug' => 'newbie',
                'emoji' => '🌱',
                'color' => '#9ca3af', // gray
                'min_cred' => 0,
                'max_cred' => 99,
                'description' => 'just getting started on your snacc journey',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'active',
                'slug' => 'active',
                'emoji' => '⚡',
                'color' => '#3b82f6', // blue
                'min_cred' => 100,
                'max_cred' => 499,
                'description' => 'actively engaging with the community',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'regular',
                'slug' => 'regular',
                'emoji' => '🔥',
                'color' => '#f59e0b', // amber
                'min_cred' => 500,
                'max_cred' => 999,
                'description' => 'a familiar face in the community',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'established',
                'slug' => 'established',
                'emoji' => '💎',
                'color' => '#8b5cf6', // purple
                'min_cred' => 1000,
                'max_cred' => 4999,
                'description' => 'well-known and respected member',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'influential',
                'slug' => 'influential',
                'emoji' => '👑',
                'color' => '#ec4899', // pink
                'min_cred' => 5000,
                'max_cred' => 9999,
                'description' => 'a true influencer in the community',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'legend',
                'slug' => 'legend',
                'emoji' => '🏆',
                'color' => '#eab308', // yellow/gold
                'min_cred' => 10000,
                'max_cred' => null, // No upper limit
                'description' => 'legendary status - the highest honor',
                'order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($tiers as $tier) {
            CredTier::updateOrCreate(
                ['slug' => $tier['slug']],
                $tier
            );
        }
    }
}
