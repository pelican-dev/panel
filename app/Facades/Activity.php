<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Services\Activity\ActivityLogService;

class Activity extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivityLogService::class;
    }
}
