<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SnaccImage extends Model
{
    protected $fillable = [
        'snacc_id',
        'image_path',
        'order',
    ];

    protected $hidden = [
        'id',
        'snacc_id',
    ];

    public function snacc(): BelongsTo
    {
        return $this->belongsTo(Snacc::class);
    }
}
