<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SnaccView extends Model
{
    protected $fillable = ['snacc_id', 'user_id'];

    public function snacc(): BelongsTo
    {
        return $this->belongsTo(Snacc::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
