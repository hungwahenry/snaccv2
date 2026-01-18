<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SnaccActivityNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $type,
        public Model $source,
        public ?User $actor = null
    ) {}

    public function via(object $notifiable): array
    {
        $channels = [];

        if ($notifiable->wantsNotification($this->type, 'database')) {
            $channels[] = 'database';
        }

        if ($notifiable->wantsNotification($this->type, 'mail')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = $this->getMessage();
        $url = $this->getActionUrl();

        return (new MailMessage)
            ->subject('New Activity on Snacc 🍿')
            ->line($message)
            ->action('View Snacc', $url);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->type,
            'source_id' => $this->source->id,
            'source_type' => class_basename($this->source),
            'actor_id' => $this->actor?->id,
            'actor_name' => $this->actor?->profile->username,
            'actor_avatar' => $this->actor?->profile->profile_photo,
            'message' => $this->getMessage(),
            'url' => $this->getActionUrl(),
        ];
    }

    protected function getMessage(): string
    {
        $actorName = $this->actor?->profile->username ?? 'Someone';

        return match ($this->type) {
            'like' => "{$actorName} liked your snacc.",
            'quote' => "{$actorName} quoted your snacc.",
            'comment' => "{$actorName} commented on your snacc.",
            'reply' => "{$actorName} replied to your comment.",
            'add' => "{$actorName} added you to their list.",
            'viral' => "Your snacc is going viral! It has reached 1000+ heat.",
            default => "{$actorName} interacted with your content.",
        };
    }

    protected function getActionUrl(): string
    {
        // Add specific logic based on source type if needed
        return match ($this->type) {
            'add' => route('profile.show', $this->actor->profile->username),
            default => route('snaccs.show', $this->source instanceof \App\Models\Comment ? $this->source->snacc_id : $this->source->id),
        };
    }
}
