<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Snacc;
use App\Models\SnaccLike;
use App\Models\User;
use App\Notifications\CommentLiked;
use App\Notifications\SnaccLiked;
use Illuminate\Support\Facades\Gate;

class LikeService
{
    /**
     * Toggle like for a snacc
     */
    public function toggleSnaccLike(Snacc $snacc, User $user): array
    {
        $like = SnaccLike::where('snacc_id', $snacc->id)
            ->where('user_id', $user->id)
            ->first();

        $isLiked = false;

        if ($like) {
            // Unlike
            Gate::forUser($user)->authorize('delete', $like);
            $like->delete();
        } else {
            // Like
            Gate::forUser($user)->authorize('create', SnaccLike::class);
            SnaccLike::create([
                'snacc_id' => $snacc->id,
                'user_id' => $user->id,
            ]);
            $isLiked = true;

            // Notify Snacc Owner (if not self-like)
            if ($snacc->user_id !== $user->id) {
                $snacc->user->notify(new SnaccLiked($snacc, $user));
            }
        }

        $snacc->refresh();

        return [
            'success' => true,
            'is_liked' => $isLiked,
            'likes_count' => $snacc->likes_count,
        ];
    }
    /**
     * Toggle like for a comment
     */
    public function toggleCommentLike(
        Comment $comment,
        User $user
    ): array {
        $like = CommentLike::where('comment_id', $comment->id)
            ->where('user_id', $user->id)
            ->first();

        $isLiked = false;

        if ($like) {
            // Unlike
            Gate::forUser($user)->authorize('delete', $like);
            $like->delete();
        } else {
            // Like
            Gate::forUser($user)->authorize('create', CommentLike::class);
            CommentLike::create([
                'comment_id' => $comment->id,
                'user_id' => $user->id,
            ]);
            $isLiked = true;

            // Notify Comment Owner (if not self-like)
            if ($comment->user_id !== $user->id) {
                $comment->user->notify(new CommentLiked($comment, $user));
            }
        }

        $comment->refresh();

        return [
            'success' => true,
            'is_liked' => $isLiked,
            'likes_count' => $comment->likes_count,
        ];
    }
}
