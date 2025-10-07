<?php

namespace App\Facades;

use App\Services\Activity\ActivityLogService;
use Illuminate\Support\Facades\Facade;

class Activity extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivityLogService::class;
    }
}
