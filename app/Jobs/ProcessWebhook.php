<?php

namespace App\Jobs;

use App\Enums\WebhookType;
use App\Models\WebhookConfiguration;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class ProcessWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param  WebhookConfiguration  $webhookConfiguration
     * @param  string  $eventName
     * @param  array<mixed>  $data
     */
    public function __construct(
        private WebhookConfiguration $webhookConfiguration,
        private string $eventName,
        private array $data
    ) {}

    /**
     * Process and send the webhook, capturing HTTP response details.
     * 
     * @return void
     */
    public function handle(): void
    {
        $data = $this->data[0] ?? [];
        if (count($data) === 1) {
            $data = reset($data);
        }

        if (is_object($data)) {
            $data = method_exists($data, 'toArray') ? $data->toArray() : (array) $data;
        }

        if (is_string($data)) {
            $data = Arr::wrap(json_decode($data, true) ?? []);
        }
        
        if (!is_array($data)) {
            $data = [];
        }
        
        $data['event'] = $this->webhookConfiguration->transformClassName($this->eventName);

        if ($this->webhookConfiguration->type === WebhookType::Discord) {
            $payload = json_encode($this->webhookConfiguration->payload);
            $tmp = $this->webhookConfiguration->replaceVars($data, $payload);
            $data = json_decode($tmp, true);

            $embeds = data_get($data, 'embeds');
            if ($embeds) {
                foreach ($embeds as &$embed) {
                    if (data_get($embed, 'has_timestamp')) {
                        $embed['timestamp'] = Carbon::now();
                        unset($embed['has_timestamp']);
                    }
                }
                $data['embeds'] = $embeds;
            }
        }

        $successful = null;
        $responseCode = null;
        $responseBody = null;
        $errorMessage = null;

        try {
            $headers = [];

            if ($this->webhookConfiguration->type === WebhookType::Regular) {
                foreach ($this->webhookConfiguration->headers as $key => $value) {
                    $headers[$key] = $this->webhookConfiguration->replaceVars($data, $value);
                }
            }
            
            $response = Http::withHeaders($headers)->post($this->webhookConfiguration->endpoint, $data);
            $responseCode = $response->status();
            $responseBody = $response->body();
            $response->throw();
            $successful = now();
        } catch (Exception $exception) {
            report($exception);
            $errorMessage = $exception->getMessage();
        }

        $this->webhookConfiguration->webhooks()->create([
            'payload' => $data,
            'successful_at' => $successful,
            'event' => $this->eventName,
            'endpoint' => $this->webhookConfiguration->endpoint,
            'response_code' => $responseCode,
            'response' => $responseBody,
            'error' => $errorMessage,
        ]);
    }
}
