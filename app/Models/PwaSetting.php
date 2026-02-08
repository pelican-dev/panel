<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $key
 * @property mixed $value
 */
class PwaSetting extends Model
{
    protected $table = 'pwa_settings';

    protected $fillable = [
        'key',
        'value',
    ];
}
