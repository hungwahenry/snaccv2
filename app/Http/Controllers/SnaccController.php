<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSnaccRequest;
use App\Models\Snacc;
use App\Services\SnaccService;
use App\Services\VibetagService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class SnaccController extends Controller
{
    public function __construct(
        protected SnaccService $snaccService,
        protected VibetagService $vibetagService
    ) {}

    public function store(StoreSnaccRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Process vibetags from content and explicit tags
        $vibetags = $this->vibetagService->processForSnacc(
            content: $validated['content'] ?? null,
            explicitTags: $validated['vibetags'] ?? []
        );

        // Check for Ghost Mode
        $isGhost = $request->boolean('is_ghost');

        if ($isGhost) {
            $hasGhostedToday = Snacc::where('user_id', auth()->id())
                ->where('is_ghost', true)
                ->whereDate('created_at', now())
                ->exists();

            if ($hasGhostedToday) {
                return redirect()->back()->withErrors(['is_ghost' => 'you can only post one ghost snacc per day.']);
            }
        }

        $snacc = $this->snaccService->createSnacc(
            userId: auth()->id(),
            universityId: auth()->user()->profile->university_id,
            content: $validated['content'] ?? null,
            visibility: $validated['visibility'],
            images: $request->file('images') ?? [],
            gifUrl: $validated['gif_url'] ?? null,
            vibetags: $vibetags,
            quotedSnaccSlug: $validated['quoted_snacc_slug'] ?? null,
            isGhost: $isGhost
        );

        return redirect()->route('snaccs.show', $snacc)->with('success', 'snacc posted successfully!');
    }

    public function destroy(Snacc $snacc): RedirectResponse
    {
        Gate::authorize('delete', $snacc);

        $this->snaccService->deleteSnacc($snacc);

        return redirect()->back()->with('success', 'snacc deleted successfully!');
    }
}
