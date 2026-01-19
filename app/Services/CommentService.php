<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Snacc;
use App\Models\User;
use App\Notifications\CommentReplied;
use App\Notifications\SnaccCommented;

class CommentService
{
    /**
     * Create a new comment or reply
     */
    public function createComment(
        int $snaccId,
        int $userId,
        ?string $content,
        ?string $gifUrl,
        ?string $parentCommentSlug = null
    ): Comment {
        $parentCommentId = null;
        if ($parentCommentSlug) {
            $parentComment = Comment::where('slug', $parentCommentSlug)->first();
            $parentCommentId = $parentComment?->id;
        }

        $comment = Comment::create([
            'snacc_id' => $snaccId,
            'user_id' => $userId,
            'content' => $content,
            'gif_url' => $gifUrl,
            'parent_comment_id' => $parentCommentId,
        ]);

        $actor = User::find($userId);
        $snacc = Snacc::find($snaccId);

        // Notify Snacc Owner (if comment and not self)
        if (!$parentCommentId && $snacc && $snacc->user_id !== $userId) {
            $snacc->user->notify(new SnaccCommented($comment, $actor));
        }

        // Notify Parent Comment Owner (if reply and not self)
        if ($parentCommentId && isset($parentComment) && $parentComment->user_id !== $userId) {
            $parentComment->user->notify(new CommentReplied($comment, $actor));
        }

        return $comment;
    }

    /**
     * Delete a comment
     */
    public function deleteComment(Comment $comment): void
    {
        // Delete all replies first (cascade will handle this via DB, but we can be explicit)
        $comment->delete();
    }

    /**
     * Get comments for a snacc with pagination
     */
    public function getCommentsForSnacc(Snacc $snacc, int $perPage = 10)
    {
        return $snacc->comments()
            ->with(['user.profile', 'repliedToUser.profile'])
            ->withCount('replies') // Only get the count, not the actual replies
            ->whereNull('parent_comment_id')
            ->orderByDesc('likes_count')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    /**
     * Get all replies for a comment
     */
    public function getRepliesForComment(Comment $comment, int $perPage = 10)
    {
        return $comment->replies()
            ->with(['user.profile', 'repliedToUser.profile'])
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);
    }
}
