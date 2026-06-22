<?php

namespace App\Providers;

use App\Events\ActivityLogged;
use App\Listeners\DispatchWebhooks;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     */
    protected $listen = [
        ActivityLogged::class => [DispatchWebhooks::class],
        'App\\Events\\*' => [DispatchWebhooks::class],
        'eloquent.created*' => [DispatchWebhooks::class],
        'eloquent.deleted*' => [DispatchWebhooks::class],
        'eloquent.updated*' => [DispatchWebhooks::class],
    ];
}
