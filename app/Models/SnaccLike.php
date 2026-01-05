<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SnaccLike extends Model
{
    protected $fillable = [
        'snacc_id',
        'user_id',
    ];

    public function snacc(): BelongsTo
    {
        return $this->belongsTo(Snacc::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::created(function (SnaccLike $like) {
            $like->snacc()->increment('likes_count');
        });

        static::deleted(function (SnaccLike $like) {
            $like->snacc()->decrement('likes_count');
        });
    }
}
