<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $fillable = ['key', 'label', 'value', 'type', 'attributes'];

    protected $casts = ['attributes' => 'array'];

    public static function getValue($key)
    {
        $setting = static::where('key', $key)->first();

        return $setting ? $setting->value : null;
    }
}
