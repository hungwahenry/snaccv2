<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CredTier extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'emoji',
        'color',
        'min_cred',
        'max_cred',
        'description',
        'order',
        'is_active',
    ];

    protected $hidden = ['id'];

    protected function casts(): array
    {
        return [
            'min_cred' => 'integer',
            'max_cred' => 'integer',
            'order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Relationships
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeForCredScore($query, int $credScore)
    {
        return $query->where('min_cred', '<=', $credScore)
            ->where(function($q) use ($credScore) {
                $q->whereNull('max_cred')
                  ->orWhere('max_cred', '>=', $credScore);
            });
    }

    /**
     * Get the tier for a given cred score
     */
    public static function getTierForScore(int $credScore): ?self
    {
        return self::active()
            ->forCredScore($credScore)
            ->ordered()
            ->first();
    }
}
