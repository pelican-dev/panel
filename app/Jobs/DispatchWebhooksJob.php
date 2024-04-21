<?php

namespace App\Jobs;

use App\Events\Event;
use App\Events\ShouldDispatchWebhooks;
use App\Models\WebhookConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DispatchWebhooksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private ShouldDispatchWebhooks $event)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        WebhookConfiguration::query()
            ->forEvent($this->event)
            ->eachById(fn (WebhookConfiguration $configuration) => DispatchWebhookForConfiguration::dispatch($configuration, $this->event));
    }
}
