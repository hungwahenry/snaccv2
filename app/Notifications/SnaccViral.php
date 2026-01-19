<?php

namespace App\Notifications;

use App\Models\Snacc;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SnaccViral extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Snacc $snacc
    ) {}

    public function via(object $notifiable): array
    {
        $channels = [];

        if ($notifiable->wantsNotification('viral', 'database')) {
            $channels[] = 'database';
        }

        if ($notifiable->wantsNotification('viral', 'mail')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Viral Snacc Alert! 🔥')
            ->line("Your snacc is going viral! It has reached 1000+ heat.")
            ->action('View Snacc', route('snaccs.show', $this->snacc));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'viral',
            'source_id' => $this->snacc->id,
            'source_type' => 'Snacc',
            'actor_id' => null, // System notification
            'actor_name' => 'Snacc System',
            'actor_avatar' => null, // Could use system logo
            'message' => "Your snacc is going viral! It has reached 1000+ heat.",
            'url' => route('snaccs.show', $this->snacc),
        ];
    }
}
