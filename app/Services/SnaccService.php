<?php

namespace App\Services;

use App\Models\Snacc;
use App\Models\SnaccImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SnaccService
{
    public function __construct(
        protected VibetagService $vibetagService
    ) {}

    public function createSnacc(
        int $userId,
        int $universityId,
        ?string $content,
        string $visibility,
        ?array $images = [],
        ?string $gifUrl = null,
        ?array $vibetags = [],
        ?string $quotedSnaccSlug = null
    ): Snacc {
        return DB::transaction(function () use (
            $userId,
            $universityId,
            $content,
            $visibility,
            $images,
            $gifUrl,
            $vibetags,
            $quotedSnaccSlug
        ) {
            $quotedSnaccId = null;
            if ($quotedSnaccSlug) {
                $quotedSnacc = Snacc::where('slug', $quotedSnaccSlug)->first();
                $quotedSnaccId = $quotedSnacc?->id;
            }

            $snacc = Snacc::create([
                'user_id' => $userId,
                'university_id' => $universityId,
                'content' => $content,
                'visibility' => $visibility,
                'gif_url' => $gifUrl,
                'quoted_snacc_id' => $quotedSnaccId,
            ]);

            if (!empty($images)) {
                $this->attachImages($snacc, $images);
            }

            if (!empty($vibetags)) {
                $this->vibetagService->attachToSnacc($snacc, $vibetags);
            }

            return $snacc->load(['user.profile', 'images', 'vibetags']);
        });
    }

    public function deleteSnacc(Snacc $snacc): void
    {
        DB::transaction(function () use ($snacc) {
            foreach ($snacc->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $snacc->update(['is_deleted' => true]);
        });
    }

    protected function attachImages(Snacc $snacc, array $images): void
    {
        foreach ($images as $index => $image) {
            if ($image instanceof UploadedFile) {
                $path = $image->store('snaccs', 'public');

                SnaccImage::create([
                    'snacc_id' => $snacc->id,
                    'image_path' => $path,
                    'order' => $index,
                ]);
            }
        }
    }
}
