<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class University extends Model
{
    protected $fillable = [
        'name',
        'acronym',
        'motto',
        'web',
        'logo',
    ];

    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class);
    }
}
