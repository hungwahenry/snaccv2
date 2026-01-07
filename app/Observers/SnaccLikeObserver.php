<?php

namespace App\Observers;

use App\Models\SnaccLike;
use App\Services\CredService;

class SnaccLikeObserver
{
    public function __construct(
        protected CredService $credService
    ) {}

    /**
     * Handle the SnaccLike "created" event.
     */
    public function created(SnaccLike $snaccLike): void
    {
        // Award cred to the snacc author
        $this->credService->awardCred(
            user: $snaccLike->snacc->user,
            action: 'like_received',
            source: $snaccLike->snacc,
            description: "Like from @{$snaccLike->user->profile->username}"
        );
    }
}
