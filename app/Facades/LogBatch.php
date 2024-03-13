<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Services\Activity\ActivityLogBatchService;

class LogBatch extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivityLogBatchService::class;
    }
}
