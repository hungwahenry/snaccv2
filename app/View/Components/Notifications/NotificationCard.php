<?php

namespace App\View\Components\Notifications;

use App\Models\NotificationType;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;

class NotificationCard extends Component
{
    public string $message;
    public string $icon;
    public string $url;
    public string $date;
    public bool $isRead;
    public bool $isGrouped;
    public int $totalCount;
    public array $actors;

    public function __construct(public DatabaseNotification $notification)
    {
        $this->prepareData();
    }

    protected function prepareData(): void
    {
        $data = $this->notification->data;
        $typeConfig = NotificationType::getCached($data['type']);
        
        // Configuration
        $verb = $typeConfig?->verb ?? 'interacted with';
        $target = $typeConfig?->target_text ?? 'content';
        $this->icon = $typeConfig?->icon ?? 'solar-bell-bold';

        // Basic Data
        $this->actors = $data['actors'] ?? [];
        $this->totalCount = $data['total_count'] ?? count($this->actors);
        $this->url = $data['url'] ?? '#';
        $this->isRead = !is_null($this->notification->read_at);
        $this->date = $this->notification->created_at->diffForHumans();
        $this->isGrouped = $this->totalCount > 1;

        // Message Generation
        $this->message = $this->generateMessage($verb, $target);
    }

    protected function generateMessage(string $verb, string $target): string
    {
        if (empty($this->actors)) {
            return "Someone {$verb} {$target}.";
        }

        $firstName = $this->actors[0]['name'];
        $othersCount = $this->totalCount - 1;

        if ($othersCount <= 0) {
            return trim("{$firstName} {$verb} {$target}.");
        } elseif ($othersCount === 1) {
            $secondName = $this->actors[1]['name'] ?? 'someone';
            return trim("{$firstName} and {$secondName} {$verb} {$target}.");
        } else {
            return trim("{$firstName} and {$othersCount} others {$verb} {$target}.");
        }
    }

    public function render()
    {
        return view('components.notifications.notification-card');
    }
}
