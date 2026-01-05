<?php

namespace App\Services;

use App\Models\Snacc;
use App\Models\Vibetag;
use Illuminate\Support\Collection;

class VibetagService
{
    /**
     * Extract vibetag names from text content
     */
    public function extractFromContent(string $content): array
    {
        preg_match_all('/#(\w+)/', $content, $matches);
        return array_unique($matches[1]);
    }

    /**
     * Find or create vibetags from an array of tag strings
     */
    public function findOrCreate(array $tags): Collection
    {
        $vibetags = collect();

        foreach ($tags as $tag) {
            $vibetag = Vibetag::findOrCreateFromString($tag);
            $vibetags->push($vibetag);
        }

        return $vibetags;
    }

    /**
     * Attach vibetags to a snacc and update usage counts
     */
    public function attachToSnacc(Snacc $snacc, array $tags): void
    {
        if (empty($tags)) {
            return;
        }

        $vibetags = $this->findOrCreate($tags);
        $vibetagIds = $vibetags->pluck('id')->toArray();

        // Sync vibetags to snacc
        $snacc->vibetags()->sync($vibetagIds);

        // Increment usage count for each vibetag
        foreach ($vibetagIds as $vibetagId) {
            Vibetag::where('id', $vibetagId)->increment('usage_count');
        }
    }

    /**
     * Process content and extract vibetags, merging with explicitly provided tags
     */
    public function processForSnacc(?string $content, array $explicitTags = []): array
    {
        $extractedTags = [];

        if (!empty($content)) {
            $extractedTags = $this->extractFromContent($content);
        }

        // Merge explicit tags with extracted tags, removing duplicates
        return array_values(array_unique(array_merge($explicitTags, $extractedTags)));
    }
}
