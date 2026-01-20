<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use App\Models\Comment;
use App\Models\User;
use App\Services\NotificationGrouper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SnaccCommented extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Comment $comment,
        public User $commenter
    ) {}

    public function via(object $notifiable): array
    {
        $grouper = app(NotificationGrouper::class);
        $existingNotification = $grouper->findGroupableNotification(
            $notifiable,
            NotificationType::COMMENT->value,
            $this->comment->snacc_id,
            'Snacc'
        );

        if ($existingNotification) {
            $grouper->updateGroupedNotification($existingNotification, $this->commenter, NotificationType::COMMENT->value);
            return [];
        }

        $channels = [];

        if ($notifiable->wantsNotification('comment', 'database')) {
            $channels[] = 'database';
        }

        if ($notifiable->wantsNotification('comment', 'mail')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Comment on Snacc 💬')
            ->line("{$this->commenter->profile->username} commented on your snacc.")
            ->action('View Snacc', route('snaccs.show', $this->comment->snacc->slug));
    }

    public function toDatabase(object $notifiable): array
    {
        $grouper = app(NotificationGrouper::class);
        $groupKey = $grouper->generateGroupKey(NotificationType::COMMENT->value, 'Snacc', $this->comment->snacc_id);
        
        return [
            'notification_group_key' => $groupKey,
            'type' => NotificationType::COMMENT->value,
            'source_id' => $this->comment->snacc_id,
            'source_type' => 'Snacc',
            'actors' => [
                [
                    'id' => $this->commenter->id,
                    'name' => $this->commenter->profile->username,
                    'avatar' => $this->commenter->profile->profile_photo,
                    'acted_at' => now()->toIso8601String(),
                ]
            ],
            'total_count' => 1,
            'message' => "{$this->commenter->profile->username} commented on your snacc.",
            'url' => route('snaccs.show', $this->comment->snacc->slug),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
