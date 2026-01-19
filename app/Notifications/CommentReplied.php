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

class CommentReplied extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Comment $reply,
        public User $replier
    ) {}

    public function via(object $notifiable): array
    {
        $grouper = app(NotificationGrouper::class);
        $existingNotification = $grouper->findGroupableNotification(
            $notifiable,
            NotificationType::REPLY->value,
            $this->reply->snacc_id,
            'Snacc'
        );

        if ($existingNotification) {
            $grouper->updateGroupedNotification($existingNotification, $this->replier, NotificationType::REPLY->value);
            return [];
        }

        $channels = [];

        if ($notifiable->wantsNotification('reply', 'database')) {
            $channels[] = 'database';
        }

        if ($notifiable->wantsNotification('reply', 'mail')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Reply to Comment ↩️')
            ->line("{$this->replier->profile->username} replied to your comment.")
            ->action('View Reply', route('snaccs.show', $this->reply->snacc_id));
    }

    public function toArray(object $notifiable): array
    {
        $grouper = app(NotificationGrouper::class);

        return [
            'type' => NotificationType::REPLY->value,
            'source_id' => $this->reply->snacc_id,
            'source_type' => 'Snacc',
            'notification_group_key' => $grouper->generateGroupKey(NotificationType::REPLY->value, 'Snacc', $this->reply->snacc_id),
            'actors' => [
                [
                    'id' => $this->replier->id,
                    'name' => $this->replier->profile->username,
                    'avatar' => $this->replier->profile->profile_photo,
                    'acted_at' => now()->toIso8601String(),
                ]
            ],
            'total_count' => 1,
            'message' => "{$this->replier->profile->username} replied to your comment.",
            'url' => route('snaccs.show', $this->reply->snacc_id),
        ];
    }
}
