<?php

namespace App\Observers;

use App\Models\Comment;
use App\Models\User;
use App\Services\CredService;
use Illuminate\Support\Str;
use App\Jobs\UpdateHeatScore;

class CommentObserver
{
    public function __construct(
        protected CredService $credService
    ) {}

    /**
     * Handle the Comment "creating" event.
     */
    public function creating(Comment $comment): void
    {
        // Auto-generate slug
        if (empty($comment->slug)) {
            $comment->slug = (string) Str::ulid();
        }
    }

    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        if ($comment->parent_comment_id) {
            // It's a reply
            $comment->parentComment()->increment('replies_count');

            // Trigger heat update on the root snacc (replies count towards heat)
            $rootSnacc = $comment->parentComment->snacc;
            if ($rootSnacc) {
                UpdateHeatScore::dispatch($rootSnacc);
            }

            // Award cred to the parent comment author
            $parentComment = $comment->parentComment;
            if ($parentComment) {
                $this->credService->awardCred(
                    user: $parentComment->user,
                    action: 'reply_received',
                    source: $comment,
                    description: "Reply from @{$comment->user->profile->username}"
                );
            }
        } else {
            // It's a top-level comment
            $comment->snacc()->increment('comments_count');

            // Update Heat
            $snacc = $comment->snacc;
            if ($snacc) {
                UpdateHeatScore::dispatch($snacc);
            }

            // Award cred to the snacc author
            $this->credService->awardCred(
                user: $comment->snacc->user,
                action: 'comment_received',
                source: $comment,
                description: "Comment from @{$comment->user->profile->username}"
            );
        }

        // Increment user's comments count
        User::where('id', $comment->user_id)->increment('comments_count');
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
        if ($comment->parent_comment_id) {
            $comment->parentComment()->decrement('replies_count');

            $rootSnacc = $comment->parentComment->snacc;
            if ($rootSnacc) {
                UpdateHeatScore::dispatch($rootSnacc);
            }
        } else {
            $comment->snacc()->decrement('comments_count');
            
            $snacc = $comment->snacc;
            if ($snacc) {
                UpdateHeatScore::dispatch($snacc);
            }
        }

        // Decrement user's comments count
        User::where('id', $comment->user_id)->decrement('comments_count');
    }
}
