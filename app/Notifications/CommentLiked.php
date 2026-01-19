<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\User;
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
        $channels = [];

        // Using generic 'like' preference since we probably don't have 'comment_like' preference yet, 
        // or we could fallback to it. Let's use 'like' for simplicity as discussed.
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
        return [
            'type' => 'like',
            'source_id' => $this->comment->snacc_id,
            'source_type' => 'Snacc',
            'actor_id' => $this->liker->id,
            'actor_name' => $this->liker->profile->username,
            'actor_avatar' => $this->liker->profile->profile_photo,
            'message' => "{$this->liker->profile->username} liked your comment.",
            'url' => route('snaccs.show', $this->comment->snacc_id),
        ];
    }
}
