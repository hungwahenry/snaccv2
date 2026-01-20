<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $channels = [
            [
                'name' => 'database',
                'label' => 'In-App Notifications',
            ],
            [
                'name' => 'mail',
                'label' => 'Email Notifications',
            ],
        ];

        foreach ($channels as $channel) {
            \App\Models\NotificationChannel::updateOrCreate(
                ['name' => $channel['name']],
                $channel
            );
        }
    }
}
