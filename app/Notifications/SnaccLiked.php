<?php

namespace App\Notifications;


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
            'like',
            $this->snacc->id,
            'Snacc'
        );

        if ($existingNotification) {
            // Update existing notification instead of creating new one
            $grouper->updateGroupedNotification($existingNotification, $this->liker, 'like');
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

    public function toDatabase(object $notifiable): array
    {
        $grouper = app(NotificationGrouper::class);
        $groupKey = $grouper->generateGroupKey('like', 'Snacc', $this->snacc->id);
        
        return [
            'notification_group_key' => $groupKey,
            'type' => 'like',
            'source_id' => $this->snacc->id,
            'source_type' => 'Snacc',
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
            'url' => route('snaccs.show', $this->snacc->slug),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
