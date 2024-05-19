<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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

    protected static function booted()
    {
        static::saved(function ($setting) {
            Cache::forget('settings');
        });

        static::deleted(function ($setting) {
            Cache::forget('settings');
        });
    }
}
