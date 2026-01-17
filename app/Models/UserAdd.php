<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAdd extends Model
{
    /** @use HasFactory<\Database\Factories\UserAddFactory> */
    use HasFactory;

    protected $table = 'user_adds';

    protected $fillable = ['user_id', 'added_user_id'];
}
