<?php

namespace App\Listeners;

use App\Events\ShouldDispatchWebhooks;
use App\Jobs\DispatchWebhooksJob;

class DispatchWebhooks
{
    public function handle(mixed $event): void
    {
        if ($event instanceof ShouldDispatchWebhooks) {
            DispatchWebhooksJob::dispatch($event);
        }
    }
}
