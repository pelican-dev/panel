<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Activity\ActivityLogTargetableService;

class ActivityLogServiceProvider extends ServiceProvider
{
    /**
     * Registers the necessary activity logger singletons scoped to the individual
     * request instances.
     */
    public function register(): void
    {
        $this->app->scoped(ActivityLogTargetableService::class);
    }
}
