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

    public function toDatabase(object $notifiable): array
    {
        // System notification, no grouping needed but we must provide standard structure
        return [
            'notification_group_key' => null,
            'actor_count' => 0,
            'data' => [
                'type' => \App\Enums\NotificationType::VIRAL->value,
                'source_id' => $this->snacc->id,
                'source_type' => 'Snacc',
                'actors' => [
                    [
                        'id' => null,
                        'name' => 'Snacc Viral',
                        'avatar' => null, // Or a system icon path
                        'acted_at' => now()->toIso8601String(),
                    ]
                ],
                'total_count' => 1,
                'message' => "Your snacc is going viral! 🔥",
                'url' => route('snaccs.show', $this->snacc->slug),
            ],
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable)['data'];
    }
}
