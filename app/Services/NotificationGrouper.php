<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;

class NotificationGrouper
{
    /**
     * Find an existing unread notification that can be grouped with a new one
     */
    public function findGroupableNotification(
        User $notifiable,
        string $type,
        int $sourceId,
        string $sourceType = 'Snacc'
    ): ?DatabaseNotification {
        if (!config('notifications.grouping.enabled', true)) {
            return null;
        }

        $windowHours = config('notifications.grouping.window_hours', 24);
        $cutoffTime = now()->subHours($windowHours);
        
        $groupKey = $this->generateGroupKey($type, $sourceType, $sourceId);

        return $notifiable->unreadNotifications()
            ->where('notification_group_key', $groupKey)
            ->where('updated_at', '>=', $cutoffTime)
            ->first();
    }

    /**
     * Generate a unique group key for fast indexed lookups
     */
    public function generateGroupKey(string $type, string $sourceType, int $sourceId): string
    {
        return "{$type}:{$sourceType}:{$sourceId}";
    }

    /**
     * Update an existing notification with a new actor
     */
    public function updateGroupedNotification(
        DatabaseNotification $notification,
        User $actor,
        string $type
    ): void {
        DB::transaction(function () use ($notification, $actor, $type) {
            $data = $notification->data;

            $existingActorIndex = collect($data['actors'])->search(function ($a) use ($actor) {
                return $a['id'] === $actor->id;
            });

            if ($existingActorIndex !== false) {
                $data['actors'][$existingActorIndex]['acted_at'] = now()->toIso8601String();
            } else {
                array_unshift($data['actors'], [
                    'id' => $actor->id,
                    'name' => $actor->profile->username,
                    'avatar' => $actor->profile->profile_photo,
                    'acted_at' => now()->toIso8601String(),
                ]);
                $data['total_count'] = $data['total_count'] + 1;
            }

            $maxActors = config('notifications.grouping.max_actors_stored', 10);
            if (count($data['actors']) > $maxActors) {
                $data['actors'] = array_slice($data['actors'], 0, $maxActors);
            }

            $notification->update([
                'data' => $data,
                'actor_count' => $data['total_count'],
                'updated_at' => now(),
            ]);
        });
    }

    /**
     * Check if a notification should be grouped
     */
    public function shouldGroup(string $notificationClass): bool
    {
        $nonGroupableClasses = [
            'App\\Notifications\\SnaccViral',
        ];

        return !in_array($notificationClass, $nonGroupableClasses);
    }
}
