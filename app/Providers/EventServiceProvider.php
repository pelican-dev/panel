<?php

namespace App\Providers;

use App\Listeners\DispatchWebhooks;
use App\Models\User;
use App\Models\Server;
use App\Models\Subuser;
use App\Models\EggVariable;
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
        //'*' => [DispatchWebhooks::class],
        'eloquent.created*' => [DispatchWebhooks::class],
        'eloquent.deleted*' => [DispatchWebhooks::class],
        'eloquent.restored*' => [DispatchWebhooks::class],
        'eloquent.updated*' => [DispatchWebhooks::class],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        User::observe(UserObserver::class);
        Server::observe(ServerObserver::class);
        Subuser::observe(SubuserObserver::class);
        EggVariable::observe(EggVariableObserver::class);
    }
}
