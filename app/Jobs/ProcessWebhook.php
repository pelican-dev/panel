<?php

namespace App\Jobs;

use App\Models\WebhookConfiguration;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
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
        private string               $eventName,
        private array                $data
    )
    {
    }

    public function handle(): void
    {
        try {
            $client = new Client();
            $client->post($this->webhookConfiguration->endpoint, [
                "json" => $this->data,
                "headers" => [
                    "User-Agent" => "pelican/panel",
                    "X-Webhook-Event" => $this->eventName,
                ]
            ]);
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
