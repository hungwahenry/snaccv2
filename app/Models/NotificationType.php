<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class NotificationType extends Model
{
    protected $primaryKey = 'type';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'type',
        'icon',
        'verb',
        'target_text',
    ];

    /**
     * Get notification type configuration from cache
     */
    public static function getCached(string $type): ?self
    {
        return Cache::remember(
            "notification_type:{$type}",
            now()->addDay(),
            fn() => self::find($type)
        );
    }
}
