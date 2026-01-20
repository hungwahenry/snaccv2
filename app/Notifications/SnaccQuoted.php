<?php

namespace App\Notifications;


use App\Models\Snacc;
use App\Models\User;
use App\Services\NotificationGrouper;
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
        $grouper = app(NotificationGrouper::class);
        $existingNotification = $grouper->findGroupableNotification(
            $notifiable,
            'quote',
            $this->snacc->id,
            'Snacc'
        );

        if ($existingNotification) {
            $grouper->updateGroupedNotification($existingNotification, $this->quoter, 'quote');
            return [];
        }

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

    public function toDatabase(object $notifiable): array
    {
        $grouper = app(NotificationGrouper::class);
        $groupKey = $grouper->generateGroupKey('quote', 'Snacc', $this->snacc->id);
        
        return [
            'notification_group_key' => $groupKey,
            'type' => 'quote',
            'source_id' => $this->snacc->id,
            'source_type' => 'Snacc',
            'actors' => [
                [
                    'id' => $this->quoter->id,
                    'name' => $this->quoter->profile->username,
                    'avatar' => $this->quoter->profile->profile_photo,
                    'acted_at' => now()->toIso8601String(),
                ]
            ],
            'total_count' => 1,
            'message' => "{$this->quoter->profile->username} quoted your snacc.",
            'url' => route('snaccs.show', $this->snacc->slug),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
