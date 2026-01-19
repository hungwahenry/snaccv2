<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use App\Models\User;
use App\Services\NotificationGrouper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserAdded extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $adder
    ) {}

    public function via(object $notifiable): array
    {
        $grouper = app(NotificationGrouper::class);
        $existingNotification = $grouper->findGroupableNotification(
            $notifiable,
            NotificationType::ADD->value,
            $this->adder->id,
            'User'
        );

        if ($existingNotification) {
            $grouper->updateGroupedNotification($existingNotification, $this->adder, NotificationType::ADD->value);
            return [];
        }

        $channels = [];

        if ($notifiable->wantsNotification('add', 'database')) {
            $channels[] = 'database';
        }

        if ($notifiable->wantsNotification('add', 'mail')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Follower ➕')
            ->line("{$this->adder->profile->username} added you to their list.")
            ->action('View Profile', route('profile.show', $this->adder->profile->username));
    }

    public function toArray(object $notifiable): array
    {
        $grouper = app(NotificationGrouper::class);

        return [
            'type' => NotificationType::ADD->value,
            'source_id' => $this->adder->id,
            'source_type' => 'User',
            'notification_group_key' => $grouper->generateGroupKey(NotificationType::ADD->value, 'User', $this->adder->id),
            'actors' => [
                [
                    'id' => $this->adder->id,
                    'name' => $this->adder->profile->username,
                    'avatar' => $this->adder->profile->profile_photo,
                    'acted_at' => now()->toIso8601String(),
                ]
            ],
            'total_count' => 1,
            'message' => "{$this->adder->profile->username} added you to their list.",
            'url' => route('profile.show', $this->adder->profile->username),
        ];
    }
}
