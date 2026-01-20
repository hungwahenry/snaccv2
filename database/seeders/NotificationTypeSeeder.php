<?php

namespace Database\Seeders;

use App\Models\NotificationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'type' => 'like',
                'icon' => 'solar-heart-bold',
                'verb' => 'liked',
                'target_text' => 'your snacc',
            ],
            [
                'type' => 'comment',
                'icon' => 'solar-chat-round-dots-bold',
                'verb' => 'commented on',
                'target_text' => 'your snacc',
            ],
            [
                'type' => 'quote',
                'icon' => 'solar-square-share-line-bold',
                'verb' => 'quoted',
                'target_text' => 'your snacc',
            ],
            [
                'type' => 'reply',
                'icon' => 'solar-reply-bold',
                'verb' => 'replied to',
                'target_text' => 'your comment',
            ],
            [
                'type' => 'add',
                'icon' => 'solar-user-plus-bold',
                'verb' => 'added you',
                'target_text' => '',
            ],
            [
                'type' => 'viral',
                'icon' => 'solar-fire-bold',
                'verb' => 'is going viral',
                'target_text' => 'your snacc',
            ],
        ];

        foreach ($types as $type) {
            NotificationType::updateOrCreate(
                ['type' => $type['type']],
                $type
            );
        }
    }
}
