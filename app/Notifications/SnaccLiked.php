<?php

namespace App\Notifications;

use App\Models\Snacc;
use App\Models\User;
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
        return [
            'type' => 'like',
            'source_id' => $this->snacc->id,
            'source_type' => 'Snacc',
            'actor_id' => $this->liker->id,
            'actor_name' => $this->liker->profile->username,
            'actor_avatar' => $this->liker->profile->profile_photo,
            'message' => "{$this->liker->profile->username} liked your snacc.",
            'url' => route('snaccs.show', $this->snacc),
        ];
    }
}
