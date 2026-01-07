<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'report_category_id',
        'reportable_type',
        'reportable_id',
        'description',
        'status',
        'reviewed_at',
        'reviewed_by',
        'moderator_notes',
        'slug',
    ];

    protected $hidden = [
        'id',
        'user_id',
        'report_category_id',
        'reportable_id',
        'reviewed_by',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ReportCategory::class, 'report_category_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeForReportableType($query, string $type)
    {
        return $query->where('reportable_type', $type);
    }

    protected static function booted(): void
    {
        static::creating(function (Report $report) {
            if (empty($report->slug)) {
                $report->slug = (string) Str::ulid();
            }
        });
    }
}
