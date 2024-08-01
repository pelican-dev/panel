<?php

namespace App\Listeners;

use App\Events\Event;
use App\Jobs\DispatchWebhooksJob;

class DispatchWebhooks
{
    public function handle(string $eventName, array $data): void
    {
        // dd($eventName, $data);

        DispatchWebhooksJob::dispatch($eventName, $data);

    }
}
