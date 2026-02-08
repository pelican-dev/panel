<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PwaSetting extends Model
{
    protected $table = 'pwa_settings';

    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'array',
    ];
}
