<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Snacc;

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
        ?int $parentCommentId = null,
        ?int $repliedToUserId = null
    ): Comment {
        return Comment::create([
            'snacc_id' => $snaccId,
            'user_id' => $userId,
            'content' => $content,
            'gif_url' => $gifUrl,
            'parent_comment_id' => $parentCommentId,
            'replied_to_user_id' => $repliedToUserId,
        ]);
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
            ->with([
                'user.profile',
                'repliedToUser.profile',
                'replies' => function($query) {
                    $query->with(['user.profile', 'repliedToUser.profile'])
                          ->orderBy('created_at', 'asc')
                          ->limit(3); // Show first 3 replies
                }
            ])
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
