<?php

namespace App\Observers;

use App\Models\Node;
use App\Events\Node as NodeEvents;

class NodeObserver
{
    /**
     * Handle the Node "created" event.
     */
    public function created(Node $node): void
    {
        event(new NodeEvents\Created($node));
    }

    /**
     * Handle the Node "updated" event.
     */
    public function updated(Node $node): void
    {
        //
    }

    /**
     * Handle the Node "deleted" event.
     */
    public function deleted(Node $node): void
    {
        event(new NodeEvents\Deleted($node));
    }

    /**
     * Handle the Node "restored" event.
     */
    public function restored(Node $node): void
    {
        //
    }

    /**
     * Handle the Node "force deleted" event.
     */
    public function forceDeleted(Node $node): void
    {
        event(new NodeEvents\Deleted($node));
    }
}
