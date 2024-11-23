<?php

namespace App\Jobs;

use App\Models\WebhookConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private WebhookConfiguration $webhookConfiguration,
        private string $eventName,
        private array $data
    ) {}

    public function handle(): void
    {
        try {
            Http::post($this->webhookConfiguration->endpoint, $this->data)->throw();
            $successful = now();
        } catch (\Exception) {
            $successful = null;
        }

        $this->webhookConfiguration->webhooks()->create([
            'payload' => $this->data,
            'successful_at' => $successful,
            'event' => $this->eventName,
            'endpoint' => $this->webhookConfiguration->endpoint,
        ]);
    }
}
