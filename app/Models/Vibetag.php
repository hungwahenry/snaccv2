<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Vibetag extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function snaccs(): BelongsToMany
    {
        return $this->belongsToMany(Snacc::class, 'snacc_vibetag')
            ->withTimestamps();
    }

    public static function findOrCreateFromString(string $tag): self
    {
        $name = ltrim($tag, '#');
        $slug = Str::lower($name);

        $vibetag = static::firstOrCreate(
            ['slug' => $slug],
            ['name' => $name]
        );

        return $vibetag;
    }

    public static function attachToSnacc(Snacc $snacc, array $tags): void
    {
        $vibetagIds = [];

        foreach ($tags as $tag) {
            $vibetag = static::findOrCreateFromString($tag);
            $vibetagIds[] = $vibetag->id;
        }

        $snacc->vibetags()->sync($vibetagIds);

        foreach ($vibetagIds as $vibetagId) {
            static::where('id', $vibetagId)->increment('usage_count');
        }
    }
}
