<?php

namespace App\Jobs;

use App\Events\ShouldDispatchWebhooks;
use App\Models\WebhookConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class DispatchWebhookForConfiguration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private WebhookConfiguration $configuration, private ShouldDispatchWebhooks $event)
    {

    }

    public function handle(): void
    {
        // Move to dedicated service to handle Webhook Model creation to save webhook history
        Http::post($this->configuration->endpoint, $this->event->getPayload())
            ->throw();
    }
}
