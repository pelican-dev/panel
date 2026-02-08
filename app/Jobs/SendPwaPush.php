<?php

namespace App\Jobs;

use App\Models\PwaPushSubscription;
use App\Services\Pwa\PwaPushService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendPwaPush implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, string>  $vapid
     */
    public function __construct(
        private int $subscriptionId,
        private array $payload,
        private array $vapid,
    ) {}

    public function handle(PwaPushService $push): void
    {
        $subscription = PwaPushSubscription::find($this->subscriptionId);

        if (!$subscription) {
            return;
        }

        $result = $push->sendToSubscription($subscription, $this->payload, $this->vapid);

        if ($result !== true) {
            Log::debug('PWA push failed', [
                'subscription_id' => $this->subscriptionId,
                'reason' => $result,
            ]);
        }
    }
}
