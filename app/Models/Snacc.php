<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Snacc extends Model
{
    protected $fillable = [
        'user_id',
        'university_id',
        'content',
        'gif_url',
        'visibility',
        'quoted_snacc_id',
        'is_deleted',
        'slug',
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
    ];

    protected $hidden = [
        'id',
        'user_id',
        'university_id',
        'quoted_snacc_id',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(SnaccImage::class)->orderBy('order');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(SnaccLike::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_comment_id');
    }

    public function vibetags(): BelongsToMany
    {
        return $this->belongsToMany(Vibetag::class, 'snacc_vibetag')
            ->withTimestamps();
    }

    public function quotedSnacc(): BelongsTo
    {
        return $this->belongsTo(Snacc::class, 'quoted_snacc_id');
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Snacc::class, 'quoted_snacc_id');
    }

    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    protected static function booted(): void
    {
        static::creating(function (Snacc $snacc) {
            if (empty($snacc->slug)) {
                $snacc->slug = (string) Str::ulid();
            }
        });

        static::created(function (Snacc $snacc) {
            if ($snacc->quoted_snacc_id) {
                Snacc::where('id', $snacc->quoted_snacc_id)->increment('quotes_count');
            }
        });

        static::deleted(function (Snacc $snacc) {
            if ($snacc->quoted_snacc_id) {
                Snacc::where('id', $snacc->quoted_snacc_id)->decrement('quotes_count');
            }
        });
    }
}
