<?php

namespace App\Enums;

enum NotificationType: string
{
    case LIKE = 'like';
    case COMMENT = 'comment';
    case QUOTE = 'quote';
    case REPLY = 'reply';
    case ADD = 'add';
    case VIRAL = 'viral';

    public function label(): string
    {
        return match($this) {
            self::LIKE => 'Like',
            self::COMMENT => 'Comment',
            self::QUOTE => 'Quote',
            self::REPLY => 'Reply',
            self::ADD => 'Follow',
            self::VIRAL => 'Viral',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::LIKE => 'solar:heart-bold',
            self::COMMENT => 'solar:chat-round-dots-bold',
            self::QUOTE => 'solar:quote-up-square-bold',
            self::REPLY => 'solar:reply-bold',
            self::ADD => 'solar:user-plus-bold',
            self::VIRAL => 'solar:fire-bold',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::LIKE => 'red',
            self::COMMENT => 'blue',
            self::QUOTE => 'purple',
            self::REPLY => 'indigo',
            self::ADD => 'green',
            self::VIRAL => 'orange',
        };
    }

    /**
     * The verb used in the message (e.g. "liked", "commented on")
     */
    public function actionVerb(): string
    {
        return match($this) {
            self::LIKE => 'liked',
            self::COMMENT => 'commented on',
            self::QUOTE => 'quoted',
            self::REPLY => 'replied to',
            self::ADD => 'added you',
            self::VIRAL => 'is going viral',
        };
    }
}
