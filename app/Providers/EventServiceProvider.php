<?php

namespace App\Providers;

use App\Events\Backup\BackupCompleted;
use App\Listeners\Backup\BackupCompletedListener;
use App\Listeners\DispatchWebhooks;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     */
    protected $listen = [
        'App\\*' => [DispatchWebhooks::class],
        'eloquent.created*' => [DispatchWebhooks::class],
        'eloquent.deleted*' => [DispatchWebhooks::class],
        'eloquent.updated*' => [DispatchWebhooks::class],
        BackupCompleted::class => [
            BackupCompletedListener::class,
        ],
    ];
}
