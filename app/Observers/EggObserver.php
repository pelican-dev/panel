<?php

namespace App\Observers;

use App\Models\Egg;
use App\Events\Egg as EggEvents;

class EggObserver
{
    /**
     * Handle the Egg "created" event.
     */
    public function created(Egg $egg): void
    {
        event(new EggEvents\Created($egg));
    }

    /**
     * Handle the Egg "updated" event.
     */
    public function updated(Egg $egg): void
    {
        //
    }

    /**
     * Handle the Egg "deleted" event.
     */
    public function deleted(Egg $egg): void
    {
        event(new EggEvents\Deleted($egg));
    }

    /**
     * Handle the Egg "restored" event.
     */
    public function restored(Egg $egg): void
    {
        //
    }

    /**
     * Handle the Egg "force deleted" event.
     */
    public function forceDeleted(Egg $egg): void
    {
        event(new EggEvents\Deleted($egg));
    }
}
