<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Comment extends Model
{
    protected $fillable = [
        'snacc_id',
        'user_id',
        'parent_comment_id',
        'replied_to_user_id',
        'content',
        'gif_url',
        'slug',
        'status',
    ];

    protected $hidden = [
        'id',
        'snacc_id',
        'user_id',
        'parent_comment_id',
        'replied_to_user_id',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function snacc(): BelongsTo
    {
        return $this->belongsTo(Snacc::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parentComment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_comment_id');
    }

    public function repliedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_to_user_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_comment_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class);
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isReportedBy(User $user): bool
    {
        return $this->reports()->where('user_id', $user->id)->exists();
    }

    protected static function booted(): void
    {
        static::creating(function (Comment $comment) {
            if (empty($comment->slug)) {
                $comment->slug = (string) Str::ulid();
            }
        });

        static::created(function (Comment $comment) {
            if ($comment->parent_comment_id) {
                $comment->parentComment()->increment('replies_count');
            } else {
                $comment->snacc()->increment('comments_count');
            }
        });

        static::deleted(function (Comment $comment) {
            if ($comment->parent_comment_id) {
                $comment->parentComment()->decrement('replies_count');
            } else {
                $comment->snacc()->decrement('comments_count');
            }
        });
    }
    public function scopeWithoutBlockedUsers($query)
    {
        $user = auth()->user();
        if (!$user) {
            return $query;
        }

        $blockedIds = $user->blockedUsers()->pluck('users.id');
        $blockedByIds = $user->blockedByUsers()->pluck('users.id');

        return $query->whereNotIn('user_id', $blockedIds)
                     ->whereNotIn('user_id', $blockedByIds);
    }
}
