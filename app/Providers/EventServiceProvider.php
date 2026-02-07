<?php

namespace App\Providers;

use App\Listeners\AdminActivityListener;
use App\Listeners\DispatchWebhooks;
use Filament\Resources\Events\RecordCreated;
use Filament\Resources\Events\RecordUpdated;
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
        RecordCreated::class => [AdminActivityListener::class],
        RecordUpdated::class => [AdminActivityListener::class],
    ];
}
