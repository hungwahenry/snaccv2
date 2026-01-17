<?php

namespace Database\Seeders;

use App\Models\HeatTier;
use Illuminate\Database\Seeder;

class HeatTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiers = [
            [
                'name' => 'Trending',
                'emoji' => '🔥',
                'color' => '#F97316', // Orange-500
                'min_heat' => 100,
                'max_heat' => 499,
                'description' => 'Post is gaining traction',
                'order' => 1,
            ],
            [
                'name' => 'Hot',
                'emoji' => '🔥🔥',
                'color' => '#EF4444', // Red-500
                'min_heat' => 500,
                'max_heat' => 999,
                'description' => 'Post is on fire',
                'order' => 2,
            ],
            [
                'name' => 'Viral',
                'emoji' => '💥',
                'color' => '#A855F7', // Purple-500
                'min_heat' => 1000,
                'max_heat' => null,
                'description' => 'Post has gone viral',
                'order' => 3,
            ],
        ];

        foreach ($tiers as $tier) {
            HeatTier::updateOrCreate(
                ['name' => $tier['name']],
                $tier
            );
        }
    }
}
