<?php

namespace App\Services;

use App\Enums\NotificationType;
use Illuminate\Notifications\DatabaseNotification;

class NotificationRenderer
{
    public function render(DatabaseNotification $notification): array
    {
        $data = $notification->data;
        $type = NotificationType::from($data['type']);

        $actors = $data['actors'];
        $totalCount = $data['total_count'];

        return [
            'type' => $type,
            'message' => $this->getMessage($data, $type),
            'url' => $data['url'] ?? '#',
            'is_read' => !is_null($notification->read_at),
            'date' => $notification->created_at->diffForHumans(),
            'icon' => $type->icon(),
            'actors' => $actors,
            'is_grouped' => $totalCount > 1,
            'total_count' => $totalCount,
        ];
    }

    private function getMessage(array $data, NotificationType $type): string
    {
        $actors = $data['actors'];
        $count = $data['total_count'];
        
        $firstName = $actors[0]['name'];
        $othersCount = $count - 1;
        $verb = $type->actionVerb();
        
        $target = match($type) {
            NotificationType::ADD => '',
            NotificationType::REPLY => 'your comment',
            default => 'your snacc',
        };

        if ($othersCount <= 0) {
            return trim("{$firstName} {$verb} {$target}.");
        } elseif ($othersCount === 1) {
             $secondName = $actors[1]['name'] ?? 'someone';
             return trim("{$firstName} and {$secondName} {$verb} {$target}.");
        } else {
            return trim("{$firstName} and {$othersCount} others {$verb} {$target}.");
        }
    }
}
