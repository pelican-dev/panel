<?php

namespace App\Providers;

use App\Listeners\DispatchWebhooks;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Laravel\Fortify\Events\TwoFactorAuthenticationChallenged;
use Laravel\Fortify\Events\TwoFactorAuthenticationEnabled;
use Vormkracht10\TwoFactorAuth\Listeners\SendTwoFactorCodeListener;

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
        TwoFactorAuthenticationChallenged::class => [SendTwoFactorCodeListener::class],
        TwoFactorAuthenticationEnabled::class => [SendTwoFactorCodeListener::class],
    ];
}
