<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use App\Models\Snacc;
use App\Models\User;
use App\Services\NotificationGrouper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SnaccLiked extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Snacc $snacc,
        public User $liker
    ) {}

    public function via(object $notifiable): array
    {
        // Check for groupable notification first
        $grouper = app(NotificationGrouper::class);
        $existingNotification = $grouper->findGroupableNotification(
            $notifiable,
            NotificationType::LIKE->value,
            $this->snacc->id,
            'Snacc'
        );

        if ($existingNotification) {
            // Update existing notification instead of creating new one
            $grouper->updateGroupedNotification($existingNotification, $this->liker, NotificationType::LIKE->value);
            return []; // Don't send new notification
        }

        // No groupable notification found, proceed with normal channels
        $channels = [];

        if ($notifiable->wantsNotification('like', 'database')) {
            $channels[] = 'database';
        }

        if ($notifiable->wantsNotification('like', 'mail')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Like on Snacc 🍿')
            ->line("{$this->liker->profile->username} liked your snacc.")
            ->action('View Snacc', route('snaccs.show', $this->snacc));
    }

    public function toArray(object $notifiable): array
    {
        $grouper = app(NotificationGrouper::class);
        
        // Use grouped format from the start
        return [
            'type' => NotificationType::LIKE->value,
            'source_id' => $this->snacc->id,
            'source_type' => 'Snacc',
            'notification_group_key' => $grouper->generateGroupKey(NotificationType::LIKE->value, 'Snacc', $this->snacc->id),
            'actors' => [
                [
                    'id' => $this->liker->id,
                    'name' => $this->liker->profile->username,
                    'avatar' => $this->liker->profile->profile_photo,
                    'acted_at' => now()->toIso8601String(),
                ]
            ],
            'total_count' => 1,
            'message' => "{$this->liker->profile->username} liked your snacc.",
            'url' => route('snaccs.show', $this->snacc),
        ];
    }
}
