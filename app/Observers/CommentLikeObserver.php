<?php

namespace App\Observers;

use App\Models\CommentLike;
use App\Services\CredService;

class CommentLikeObserver
{
    public function __construct(
        protected CredService $credService
    ) {}

    /**
     * Handle the CommentLike "created" event.
     */
    public function created(CommentLike $commentLike): void
    {
        // Award cred to the comment author
        $this->credService->awardCred(
            user: $commentLike->comment->user,
            action: 'like_received',
            source: $commentLike->comment,
            description: "Like from @{$commentLike->user->profile->username}"
        );
    }
}
