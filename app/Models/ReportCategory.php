<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ReportCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'applies_to',
        'order',
        'is_active',
        'slug',
    ];

    protected $hidden = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForType($query, string $type)
    {
        return $query->where(function ($q) use ($type) {
            $q->where('applies_to', $type)
              ->orWhere('applies_to', 'all');
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    protected static function booted(): void
    {
        static::creating(function (ReportCategory $category) {
            if (empty($category->slug)) {
                $category->slug = (string) Str::ulid();
            }
        });
    }
}
