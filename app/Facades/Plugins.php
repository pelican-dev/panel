<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Services\Helpers\PluginService;

class Plugins extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PluginService::class;
    }
}
