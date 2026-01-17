<?php

namespace App\Observers;

use App\Models\SnaccLike;
use App\Services\CredService;

use App\Jobs\UpdateHeatScore;

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
        // Update Heat
        $snacc = $snaccLike->snacc;
        if ($snacc) {
            UpdateHeatScore::dispatch($snacc);
        }

        // Award cred to the snacc author
        $this->credService->awardCred(
            user: $snaccLike->snacc->user,
            action: 'like_received',
            source: $snaccLike->snacc,
            description: "Like from @{$snaccLike->user->profile->username}"
        );
    }

    /**
     * Handle the SnaccLike "deleted" event.
     */
    public function deleted(SnaccLike $snaccLike): void
    {
        // Update Heat (unlike)
        $snacc = $snaccLike->snacc;
        if ($snacc) {
            UpdateHeatScore::dispatch($snacc);
        }
    }
}
