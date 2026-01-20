<?php

namespace App\Notifications;


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
            'like',
            $this->comment->snacc_id,
            'Snacc'
        );

        if ($existingNotification) {
            $grouper->updateGroupedNotification($existingNotification, $this->liker, 'like');
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
            ->action('View Comment', route('snaccs.show', $this->comment->snacc->slug));
    }

    public function toDatabase(object $notifiable): array
    {
        $grouper = app(NotificationGrouper::class);
        $groupKey = $grouper->generateGroupKey('like', 'Snacc', $this->comment->snacc_id);
        
        return [
            'notification_group_key' => $groupKey,
            'type' => 'like',
            'source_id' => $this->comment->snacc_id,
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
            'message' => "{$this->liker->profile->username} liked your comment.",
            'url' => route('snaccs.show', $this->comment->snacc->slug),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
