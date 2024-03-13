<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Services\Activity\ActivityLogTargetableService;

class LogTarget extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivityLogTargetableService::class;
    }
}
