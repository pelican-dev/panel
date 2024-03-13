<?php

namespace App\Extensions\Facades;

use Illuminate\Support\Facades\Facade;

class Theme extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'extensions.themes';
    }
}
