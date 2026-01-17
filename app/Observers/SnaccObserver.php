<?php

namespace App\Observers;

use App\Models\Snacc;
use App\Models\User;
use App\Services\CredService;
use App\Jobs\UpdateHeatScore;
use Illuminate\Support\Str;

class SnaccObserver
{
    public function __construct(
        protected CredService $credService
    ) {}

    /**
     * Handle the Snacc "creating" event.
     */
    public function creating(Snacc $snacc): void
    {
        // Auto-generate slug
        if (empty($snacc->slug)) {
            $snacc->slug = (string) Str::ulid();
        }
    }

    /**
     * Handle the Snacc "created" event.
     */
    public function created(Snacc $snacc): void
    {
        // Increment quotes count on the quoted snacc
        if ($snacc->quoted_snacc_id) {
            Snacc::where('id', $snacc->quoted_snacc_id)->increment('quotes_count');

            // Award cred to the author of the quoted snacc
            $quotedSnacc = Snacc::find($snacc->quoted_snacc_id);
            if ($quotedSnacc) {
                $this->credService->awardCred(
                    user: $quotedSnacc->user,
                    action: 'quote_received',
                    source: $snacc,
                    description: "Post quoted by @{$snacc->user->profile->username}"
                );
            }
        }

        // Award cred to the post creator
        $this->credService->awardCred(
            user: $snacc->user,
            action: 'post_created',
            source: $snacc,
            description: 'Created a new post'
        );

        // Increment user's posts count
        User::where('id', $snacc->user_id)->increment('posts_count');

        // Initialize heat calculation
        UpdateHeatScore::dispatch($snacc);
    }

    /**
     * Handle the Snacc "updated" event.
     */
    public function updated(Snacc $snacc): void
    {
        // Recalculate heat when engagement changes
        if ($snacc->wasChanged(['likes_count', 'comments_count', 'quotes_count', 'views_count'])) {
            UpdateHeatScore::dispatch($snacc);
        }

        // Deduct cred if post is soft-deleted
        if ($snacc->wasChanged('is_deleted') && $snacc->is_deleted) {
            $this->credService->deductCred(
                user: $snacc->user,
                action: 'post_deleted',
                source: $snacc,
                description: 'Deleted a post'
            );
        }
    }

    /**
     * Handle the Snacc "deleted" event.
     */
    public function deleted(Snacc $snacc): void
    {
        // Decrement quotes count on the quoted snacc
        if ($snacc->quoted_snacc_id) {
            Snacc::where('id', $snacc->quoted_snacc_id)->decrement('quotes_count');
        }

        // Decrement user's posts count
        User::where('id', $snacc->user_id)->decrement('posts_count');
    }
}
