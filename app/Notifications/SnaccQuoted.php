<?php

namespace App\Notifications;

use App\Models\Snacc;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SnaccQuoted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Snacc $snacc,
        public User $quoter
    ) {}

    public function via(object $notifiable): array
    {
        $channels = [];

        if ($notifiable->wantsNotification('quote', 'database')) {
            $channels[] = 'database';
        }

        if ($notifiable->wantsNotification('quote', 'mail')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Quote on Snacc 🗣️')
            ->line("{$this->quoter->profile->username} quoted your snacc.")
            ->action('View Quote', route('snaccs.show', $this->snacc));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'quote',
            'source_id' => $this->snacc->id,
            'source_type' => 'Snacc',
            'actor_id' => $this->quoter->id,
            'actor_name' => $this->quoter->profile->username,
            'actor_avatar' => $this->quoter->profile->profile_photo,
            'message' => "{$this->quoter->profile->username} quoted your snacc.",
            'url' => route('snaccs.show', $this->snacc),
        ];
    }
}
