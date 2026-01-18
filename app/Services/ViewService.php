<?php

namespace App\Services;

use App\Models\Snacc;
use App\Models\SnaccView;
use Illuminate\Support\Facades\DB;

class ViewService
{
    public function __construct(
        protected HeatService $heatService
    ) {}

    /**
     * Record a unique view for a snacc.
     * Returns true if a new view was recorded, false if it was already viewed.
     */
    public function recordView(Snacc $snacc): bool
    {
        // Only track logged-in users
        if (!auth()->check()) {
            return false;
        }

        $userId = auth()->id();

        // Check if view already exists
        $viewExists = SnaccView::where('snacc_id', $snacc->id)
            ->where('user_id', $userId)
            ->exists();

        if ($viewExists) {
            return false;
        }

        // Create new view and increment counter atomically
        return DB::transaction(function () use ($snacc, $userId) {
            // Double-check existence inside transaction (though unique constraint handles race conditions)
            try {
                SnaccView::create([
                    'snacc_id' => $snacc->id,
                    'user_id' => $userId,
                ]);

                // Increment view count on snacc
                $snacc->increment('views_count');

                // Update heat score
                $this->heatService->updateHeat($snacc);

                return true;
            } catch (\Exception $e) {
                // Unique constraint violation means view existed, just return false
                return false;
            }
        });
    }
}
