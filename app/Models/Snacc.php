<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use App\Models\HeatTier;

class Snacc extends Model
{
    protected $fillable = [
        'user_id',
        'university_id',
        'content',
        'gif_url',
        'visibility',
        'quoted_snacc_id',
        'is_deleted',
        'is_ghost',
        'slug',
        'status',
        'heat_score',
        'heat_peak_at',
        'heat_calculated_at',
        'views_count',
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
        'is_ghost' => 'boolean',
        'heat_score' => 'integer',
        'views_count' => 'integer',
        'heat_peak_at' => 'datetime',
        'heat_calculated_at' => 'datetime',
    ];

    protected $hidden = [
        'id',
        'user_id',
        'university_id',
        'quoted_snacc_id',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(SnaccImage::class)->orderBy('order');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(SnaccLike::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_comment_id');
    }

    public function vibetags(): BelongsToMany
    {
        return $this->belongsToMany(Vibetag::class, 'snacc_vibetag')
            ->withTimestamps();
    }

    public function quotedSnacc(): BelongsTo
    {
        return $this->belongsTo(Snacc::class, 'quoted_snacc_id');
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Snacc::class, 'quoted_snacc_id');
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isReportedBy(User $user): bool
    {
        return $this->reports()->where('user_id', $user->id)->exists();
    }

    public function getHeatTierAttribute(): ?HeatTier
    {
        return HeatTier::getTierForScore($this->heat_score);
    }

    // Scopes
    public function scopeTrending($query)
    {
        return $query->where('heat_score', '>', 0)
            ->where('created_at', '>=', now()->subDays(4))
            ->orderByDesc('heat_score')
            ->orderByDesc('created_at');
    }

    public function scopeForUniversity($query, int $universityId)
    {
        return $query->where('university_id', $universityId);
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', false);
    }

    public function scopeGlobal($query)
    {
        return $query->where('visibility', 'global');
    }

    public function scopeSearch($query, string $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            // Search in content
            $q->where('content', 'LIKE', "%{$searchTerm}%")
                // Search in vibetags
                ->orWhereHas('vibetags', function ($vibeQuery) use ($searchTerm) {
                    $vibeQuery->where('name', 'LIKE', "%{$searchTerm}%")
                             ->orWhere('slug', 'LIKE', "%{$searchTerm}%");
                })
                // Search in author username (excluding ghosts)
                ->orWhere(function ($subQ) use ($searchTerm) {
                    $subQ->where('is_ghost', false)
                         ->whereHas('user.profile', function ($profileQuery) use ($searchTerm) {
                             $profileQuery->where('username', 'LIKE', "%{$searchTerm}%");
                         });
                });
        });
    }

    public function toArray()
    {
        $array = parent::toArray();

        // If ghost, remove user info to leaks
        if ($this->is_ghost) {
            unset($array['user']);
            unset($array['user_id']);
            if (isset($this->relations['user'])) {
                unset($this->relations['user']);
            }
        }

        return $array;
    }
}
