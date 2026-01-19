<?php

namespace App\Notifications;

use App\Models\User;
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
        return [
            'type' => 'add',
            'source_id' => $this->adder->id,
            'source_type' => 'User', 
            'actor_id' => $this->adder->id,
            'actor_name' => $this->adder->profile->username,
            'actor_avatar' => $this->adder->profile->profile_photo,
            'message' => "{$this->adder->profile->username} added you to their list.",
            'url' => route('profile.show', $this->adder->profile->username),
        ];
    }
}
