<?php

namespace App\Providers;

use App\Listeners\DispatchWebhooks;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     */
    protected $listen = [
        //'*' => [DispatchWebhooks::class],
        'eloquent.created*' => [DispatchWebhooks::class],
        'eloquent.deleted*' => [DispatchWebhooks::class],
        'eloquent.updated*' => [DispatchWebhooks::class],
    ];
}
