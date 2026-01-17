<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'cred_score',
        'login_streak',
        'last_login_date',
        'daily_cred_earned',
        'daily_cred_reset_date',
        'cred_tier_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'id',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'cred_score' => 'integer',
            'login_streak' => 'integer',
            'last_login_date' => 'date',
            'daily_cred_earned' => 'integer',
            'daily_cred_reset_date' => 'date',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function receivedReports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function credTier(): HasOne
    {
        return $this->hasOne(CredTier::class, 'id', 'cred_tier_id');
    }

    public function credTransactions(): HasMany
    {
        return $this->hasMany(CredTransaction::class);
    }

    public function addedUsers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_adds', 'user_id', 'added_user_id')
            ->withTimestamps();
    }

    public function addedByUsers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_adds', 'added_user_id', 'user_id')
            ->withTimestamps();
    }

    public function isAddedBy(User $user): bool
    {
        return $this->addedByUsers()->where('user_id', $user->id)->exists();
    }
}

