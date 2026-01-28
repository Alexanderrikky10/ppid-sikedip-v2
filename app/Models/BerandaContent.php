<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BerandaContent extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'media',
        'is_active',
    ];

    protected $casts = [
        'media' => 'array',
        'is_active' => 'boolean',
    ];
}
