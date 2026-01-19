<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\User;
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
            ->action('View Snacc', route('snaccs.show', $this->comment->snacc_id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'comment',
            'source_id' => $this->comment->snacc_id,
            'source_type' => 'Snacc',
            'actor_id' => $this->commenter->id,
            'actor_name' => $this->commenter->profile->username,
            'actor_avatar' => $this->commenter->profile->profile_photo,
            'message' => "{$this->commenter->profile->username} commented on your snacc.",
            'url' => route('snaccs.show', $this->comment->snacc_id),
        ];
    }
}
