<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'university_id',
        'username',
        'graduation_year',
        'gender',
        'bio',
        'profile_photo',
    ];

    protected $casts = [
        'graduation_year' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    // Scopes
    public function scopeSearch($query, string $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('username', 'LIKE', "%{$searchTerm}%")
              ->orWhere('bio', 'LIKE', "%{$searchTerm}%")
              ->orWhereHas('university', function ($uniQuery) use ($searchTerm) {
                  $uniQuery->where('name', 'LIKE', "%{$searchTerm}%")
                           ->orWhere('acronym', 'LIKE', "%{$searchTerm}%");
              });
        });
    }
}
