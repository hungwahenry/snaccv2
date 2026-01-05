<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSnaccRequest;
use App\Models\Snacc;
use App\Services\SnaccService;
use App\Services\VibetagService;
use Illuminate\Http\RedirectResponse;

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

        $this->snaccService->createSnacc(
            userId: auth()->id(),
            universityId: auth()->user()->profile->university_id,
            content: $validated['content'] ?? null,
            visibility: $validated['visibility'],
            images: $request->file('images') ?? [],
            gifUrl: $validated['gif_url'] ?? null,
            vibetags: $vibetags,
            quotedSnaccId: $validated['quoted_snacc_id'] ?? null
        );

        return redirect()->route('home')->with('success', 'snacc posted successfully!');
    }

    public function destroy(Snacc $snacc): RedirectResponse
    {
        if ($snacc->user_id !== auth()->id()) {
            abort(403);
        }

        $this->snaccService->deleteSnacc($snacc);

        return redirect()->back()->with('success', 'snacc deleted successfully!');
    }
}
