<?php

namespace App\Settings;

use Cache;

class Helpers
{
    public static function settings($key)
    {
        return Cache::get('settings')->where('key', $key)->first()->value;
    }
}
