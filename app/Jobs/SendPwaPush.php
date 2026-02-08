<?php

namespace App\Jobs;

use App\Models\PwaPushSubscription;
use App\Services\Pwa\PwaPushService;
use App\Services\Pwa\PwaSettingsRepository;
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
     */
    public function __construct(
        private int $subscriptionId,
        private array $payload,
    ) {}

    public function handle(PwaPushService $push, PwaSettingsRepository $settings): void
    {
        $subscription = PwaPushSubscription::find($this->subscriptionId);

        if (!$subscription) {
            return;
        }

        $vapid = [
            'subject' => $settings->get('vapid_subject', ''),
            'publicKey' => $settings->get('vapid_public_key', ''),
            'privateKey' => $settings->get('vapid_private_key', ''),
        ];

        if (!$vapid['publicKey'] || !$vapid['privateKey']) {
            Log::debug('PWA push skipped: VAPID keys missing', [
                'subscription_id' => $this->subscriptionId,
            ]);

            return;
        }

        $result = $push->sendToSubscription($subscription, $this->payload, $vapid);

        if ($result !== true) {
            Log::debug('PWA push failed', [
                'subscription_id' => $this->subscriptionId,
                'reason' => $result,
            ]);
        }
    }
}
