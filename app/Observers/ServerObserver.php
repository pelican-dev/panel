<?php

namespace App\Observers;

use App\Models\Server;
use App\Events\Server as ServerEvents;

class ServerObserver
{
    /**
     * Handle the Server "created" event.
     */
    public function created(Server $server): void
    {
        event(new ServerEvents\Created($server));
    }

    /**
     * Handle the Server "updated" event.
     */
    public function updated(Server $server): void
    {
        event(new ServerEvents\Updated($server));
    }

    /**
     * Handle the Server "deleted" event.
     */
    public function deleted(Server $server): void
    {
        event(new ServerEvents\Deleted($server));
    }

    /**
     * Handle the Server "restored" event.
     */
    public function restored(Server $server): void
    {
        //
    }

    /**
     * Handle the Server "force deleted" event.
     */
    public function forceDeleted(Server $server): void
    {
        event(new ServerEvents\Deleted($server));
    }
}
