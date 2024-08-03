<?php

namespace App\Providers;

use App\Events\DatabaseHost as DatabaseHostEvents;
use App\Events\Egg as EggEvents;
use App\Events\Node as NodeEvents;
use App\Events\Server as ServerEvents;
use App\Events\User as UserEvents;
use App\Listeners\Webhook\DatabaseHostWebhookListener;
use App\Listeners\Webhook\EggWebhookListener;
use App\Listeners\Webhook\NodeWebhookListener;
use App\Listeners\Webhook\ServerWebhookListener;
use App\Listeners\Webhook\UserWebhookListener;
use App\Models\DatabaseHost;
use App\Models\Egg;
use App\Models\Node;
use App\Models\User;
use App\Models\Server;
use App\Models\Subuser;
use App\Models\EggVariable;
use App\Observers\DatabaseHostObserver;
use App\Observers\EggObserver;
use App\Observers\NodeObserver;
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
        DatabaseHostEvents\Created::class => [DatabaseHostWebhookListener::class],
        DatabaseHostEvents\Deleted::class => [DatabaseHostWebhookListener::class],
        EggEvents\Created::class => [EggWebhookListener::class],
        EggEvents\Deleted::class => [EggWebhookListener::class],
        NodeEvents\Created::class => [NodeWebhookListener::class],
        NodeEvents\Deleted::class => [NodeWebhookListener::class],
        ServerEvents\Created::class => [ServerWebhookListener::class],
        ServerEvents\Deleted::class => [ServerWebhookListener::class],
        UserEvents\Created::class => [UserWebhookListener::class],
        UserEvents\Deleted::class => [UserWebhookListener::class],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        DatabaseHost::observe(DatabaseHostObserver::class);
        Egg::observe(EggObserver::class);
        EggVariable::observe(EggVariableObserver::class);
        Node::observe(NodeObserver::class);
        Server::observe(ServerObserver::class);
        Subuser::observe(SubuserObserver::class);
        User::observe(UserObserver::class);
    }
}
