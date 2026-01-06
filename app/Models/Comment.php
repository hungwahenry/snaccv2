<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $fillable = [
        'snacc_id',
        'user_id',
        'parent_comment_id',
        'replied_to_user_id',
        'content',
        'gif_url',
    ];

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

    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    protected static function booted(): void
    {
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
}
