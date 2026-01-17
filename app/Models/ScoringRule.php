<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoringRule extends Model
{
    protected $fillable = [
        'key',
        'value',
        'category',
        'description',
        'is_active',
    ];

    protected $casts = [
        'value' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Get value for a key, with caching
     */
    public static function getValue(string $key, float $default = 0.0): float
    {
        // For now, no cache to ensure live updates. Can add Cache::remember later.
        $rule = static::where('key', $key)->where('is_active', true)->first();
        return $rule ? $rule->value : $default;
    }
}
