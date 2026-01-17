<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeatTier extends Model
{
    protected $fillable = [
        'name',
        'emoji',
        'color',
        'min_heat',
        'max_heat',
        'description',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'min_heat' => 'integer',
            'max_heat' => 'integer',
            'order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the tier for a given heat score
     */
    public static function getTierForScore(int $heatScore): ?self
    {
        return static::where('is_active', true)
            ->where('min_heat', '<=', $heatScore)
            ->where(function ($query) use ($heatScore) {
                $query->whereNull('max_heat')
                    ->orWhere('max_heat', '>=', $heatScore);
            })
            ->orderBy('min_heat', 'desc')
            ->first();
    }

    /**
     * Get all active tiers ordered
     */
    public static function getActiveTiers()
    {
        return static::where('is_active', true)
            ->orderBy('order')
            ->get();
    }
}
