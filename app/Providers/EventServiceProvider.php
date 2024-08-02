<?php

namespace App\Providers;

use App\Events\Server as ServerEvents;
use App\Events\User as UserEvents;
use App\Events\Egg as EggEvents;
use App\Listeners\WebhookListener;
use App\Models\Egg;
use App\Models\User;
use App\Models\Server;
use App\Models\Subuser;
use App\Models\EggVariable;
use App\Observers\EggObserver;
use App\Observers\UserObserver;
use App\Observers\ServerObserver;
use App\Observers\SubuserObserver;
use App\Observers\EggVariableObserver;
use App\Events\Server\Installed as ServerInstalledEvent;
use App\Notifications\ServerInstalled as ServerInstalledNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     */
    protected $listen = [
        ServerInstalledEvent::class => [ServerInstalledNotification::class],
        UserEvents\Created::class => [WebhookListener::class],
        UserEvents\Deleted::class => [WebhookListener::class],
        EggEvents\Created::class => [WebhookListener::class],
        EggEvents\Deleted::class => [WebhookListener::class],
        ServerEvents\Created::class => [WebhookListener::class],
        ServerEvents\Deleted::class => [WebhookListener::class],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
        Egg::observe(EggObserver::class);
        User::observe(UserObserver::class);
        Server::observe(ServerObserver::class);
        Subuser::observe(SubuserObserver::class);
        EggVariable::observe(EggVariableObserver::class);
    }
}
