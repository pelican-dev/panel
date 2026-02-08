<?php

namespace App\Services\Pwa;

use App\Models\PwaPushSubscription;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class PwaPushService
{
    public function canSend(): bool
    {
        return class_exists(\Minishlink\WebPush\WebPush::class)
            && class_exists(\Minishlink\WebPush\Subscription::class);
    }

    /**
     * Send a push notification to a subscription.
     *
     * Returns true on success, or an error reason string on failure.
     * Automatically deletes stale/expired subscriptions (404, 410, VAPID mismatch).
     */
    public function sendToSubscription(PwaPushSubscription $subscription, array $payload, array $vapid): true|string
    {
        if (!$this->canSend()) {
            return 'WebPush library not available';
        }

        // Ensure subject has a value — minishlink/web-push requires it
        // Fall back to the app URL if no mailto: subject is configured
        if (empty($vapid['subject'])) {
            $vapid['subject'] = config('app.url', 'https://localhost');
        }

        $webPush = new WebPush([
            'VAPID' => $vapid,
        ]);

        $webPush->queueNotification(
            Subscription::create([
                'endpoint' => $subscription->endpoint,
                'keys' => [
                    'p256dh' => $subscription->public_key,
                    'auth' => $subscription->auth_token,
                ],
            ]),
            json_encode($payload, JSON_UNESCAPED_SLASHES)
        );

        foreach ($webPush->flush() as $report) {
            if ($report->isSuccess()) {
                return true;
            }

            $statusCode = $report->getResponse()?->getStatusCode();

            if (in_array($statusCode, [400, 403, 404, 410], true)) {
                $subscription->delete();
            }

            return $report->getReason() ?: "Push failed with status $statusCode";
        }

        return 'No reports returned from WebPush flush';
    }
}
