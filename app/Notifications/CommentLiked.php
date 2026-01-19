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

class CommentLiked extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Comment $comment,
        public User $liker
    ) {}

    public function via(object $notifiable): array
    {
        // Check for groupable notification first
        $grouper = app(NotificationGrouper::class);
        $existingNotification = $grouper->findGroupableNotification(
            $notifiable,
            NotificationType::LIKE->value,
            $this->comment->snacc_id,
            'Snacc'
        );

        if ($existingNotification) {
            $grouper->updateGroupedNotification($existingNotification, $this->liker, NotificationType::LIKE->value);
            return [];
        }

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
            ->subject('New Like on Comment 👍')
            ->line("{$this->liker->profile->username} liked your comment.")
            ->action('View Comment', route('snaccs.show', $this->comment->snacc_id));
    }

    public function toArray(object $notifiable): array
    {
        $grouper = app(NotificationGrouper::class);

        return [
            'type' => NotificationType::LIKE->value,
            'source_id' => $this->comment->snacc_id,
            'source_type' => 'Snacc',
            'notification_group_key' => $grouper->generateGroupKey(NotificationType::LIKE->value, 'Snacc', $this->comment->snacc_id),
            'actors' => [
                [
                    'id' => $this->liker->id,
                    'name' => $this->liker->profile->username,
                    'avatar' => $this->liker->profile->profile_photo,
                    'acted_at' => now()->toIso8601String(),
                ]
            ],
            'total_count' => 1,
            'message' => "{$this->liker->profile->username} liked your comment.",
            'url' => route('snaccs.show', $this->comment->snacc_id),
        ];
    }
}
