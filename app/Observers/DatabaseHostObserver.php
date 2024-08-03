<?php

namespace App\Observers;

use App\Models\DatabaseHost;
use App\Events\DatabaseHost as DatabaseHostEvents;

class DatabaseHostObserver
{
    /**
     * Handle the DatabaseHost "created" event.
     */
    public function created(DatabaseHost $databaseHost): void
    {
        event(new DatabaseHostEvents\Created($databaseHost));
    }

    /**
     * Handle the DatabaseHost "updated" event.
     */
    public function updated(DatabaseHost $databaseHost): void
    {
        //
    }

    /**
     * Handle the DatabaseHost "deleted" event.
     */
    public function deleted(DatabaseHost $databaseHost): void
    {
        event(new DatabaseHostEvents\Deleted($databaseHost));
    }

    /**
     * Handle the DatabaseHost "restored" event.
     */
    public function restored(DatabaseHost $databaseHost): void
    {
        //
    }

    /**
     * Handle the DatabaseHost "force deleted" event.
     */
    public function forceDeleted(DatabaseHost $databaseHost): void
    {
        event(new DatabaseHostEvents\Deleted($databaseHost));
    }
}
