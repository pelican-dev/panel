<?php

namespace App\Listeners;

use App\Events\Event;
use App\Events\ShouldDispatchWebhooks;
use App\Jobs\DispatchWebhooksJob;

class DispatchWebhooks
{
    /**
     * Handle the event.
     */
    public function handle(mixed $event): void
    {
        if ($event instanceof ShouldDispatchWebhooks) {
            DispatchWebhooksJob::dispatch($event);
        }
    }
}
