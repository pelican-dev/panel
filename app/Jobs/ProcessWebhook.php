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
     * @param  array<mixed>  $data
     */
    public function __construct(
        private WebhookConfiguration $webhookConfiguration,
        private string $eventName,
        private array $data
    ) {}

    public function handle(): void
    {
        $data = $this->data[0] ?? [];
        if (count($data) === 1) {
            $data = reset($data);
        }

        foreach ($data as $d){
            $d = Arr::wrap(json_decode($d, true) ?? []);
            $d['event'] = $this->webhookConfiguration->transformClassName($this->eventName);

            if ($this->webhookConfiguration->type === WebhookType::Discord) {
                $payload = json_encode($this->webhookConfiguration->payload);
                $tmp = $this->webhookConfiguration->replaceVars($d, $payload);
                $d = json_decode($tmp, true);

                $embeds = data_get($d, 'embeds');
                if ($embeds) {
                    foreach ($embeds as &$embed) {
                        if (data_get($embed, 'has_timestamp')) {
                            $embed['timestamp'] = Carbon::now();
                            unset($embed['has_timestamp']);
                        }
                    }
                    $d['embeds'] = $embeds;
                }
            }

            try {
                $customHeaders = $this->webhookConfiguration->headers;
                $headers = [];
                foreach ($customHeaders as $key => $value) {
                    $headers[$key] = $this->webhookConfiguration->replaceVars($d, $value);
                }

                Http::withHeaders($headers)->post($this->webhookConfiguration->endpoint, $d)->throw();
                $successful = now();
            } catch (Exception $exception) {
                report($exception->getMessage());
                $successful = null;
            }

            $this->webhookConfiguration->webhooks()->create([
                'payload' => $d,
                'successful_at' => $successful,
                'event' => $this->eventName,
                'endpoint' => $this->webhookConfiguration->endpoint,
            ]);
        }
    }
}
