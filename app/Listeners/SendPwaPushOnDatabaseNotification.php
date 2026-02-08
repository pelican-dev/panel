<?php

namespace App\Listeners;

use App\Models\PwaPushSubscription;
use App\Services\Pwa\PwaPushService;
use App\Services\Pwa\PwaSettingsRepository;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Notifications\Messages\MailMessage;

class SendPwaPushOnDatabaseNotification
{
    public function __construct(
        private PwaPushService $push,
        private PwaSettingsRepository $settings,
    ) {}

    public function handle(NotificationSent $event): void
    {
        if (!in_array($event->channel, ['database', 'mail'], true)) {
            return;
        }

        if (!$this->settings->get('push_enabled', false)) {
            return;
        }

        if ($event->channel === 'database'
            && !$this->settings->get('push_send_on_database_notifications', true)) {
            return;
        }

        if ($event->channel === 'mail'
            && !$this->settings->get('push_send_on_mail_notifications', false)) {
            return;
        }

        $notifiable = $event->notifiable;

        $vapid = [
            'subject' => $this->settings->get('vapid_subject', ''),
            'publicKey' => $this->settings->get('vapid_public_key', ''),
            'privateKey' => $this->settings->get('vapid_private_key', ''),
        ];

        if (!$vapid['publicKey'] || !$vapid['privateKey']) {
            return;
        }

        $payload = $this->buildPayload($event);
        if (!$payload) {
            return;
        }

        $subscriptions = PwaPushSubscription::query()
            ->where('notifiable_type', $notifiable->getMorphClass())
            ->where('notifiable_id', $notifiable->getKey())
            ->get();

        foreach ($subscriptions as $subscription) {
            $result = $this->push->sendToSubscription($subscription, $payload, $vapid);
            if ($result !== true) {
                logger()->debug('PWA push failed', [
                    'subscription_id' => $subscription->getKey(),
                    'reason' => $result,
                ]);
            }
        }
    }

    private function buildPayload(NotificationSent $event): ?array
    {
        if (method_exists($event->notification, 'toPwaPush')) {
            $custom = $event->notification->toPwaPush($event->notifiable);
            if (is_array($custom)) {
                return $this->normalizePayload($custom);
            }
        }

        $data = [];

        if (method_exists($event->notification, 'toArray')) {
            $data = (array) $event->notification->toArray($event->notifiable);
        }

        if (!empty($data)) {
            return $this->normalizePayload($data);
        }

        if ($event->channel === 'mail' && method_exists($event->notification, 'toMail')) {
            $mail = $event->notification->toMail($event->notifiable);
            if ($mail instanceof MailMessage) {
                return $this->normalizePayload($this->payloadFromMailMessage($mail));
            }
        }

        return null;
    }

    private function payloadFromMailMessage(MailMessage $mail): array
    {
        $greeting = $mail->greeting;
        $introLines = $mail->introLines ?? [];
        $outroLines = $mail->outroLines ?? [];
        $subject = $mail->subject;

        $title = $subject ?: ($greeting ?: config('app.name', 'Pelican'));
        $body = '';

        if (!empty($introLines)) {
            $body = implode(' ', $introLines);
        } elseif (!empty($outroLines)) {
            $body = implode(' ', $outroLines);
        } elseif ($greeting) {
            $body = $greeting;
        }

        $payload = [
            'title' => $title,
            'body' => $body,
            'url' => $mail->actionUrl ?: url('/app'),
        ];

        if ($mail->actionText && $mail->actionUrl) {
            $payload['actions'] = [
                [
                    'action' => 'open',
                    'title' => $mail->actionText,
                ],
            ];
        }

        return $payload;
    }

    private function normalizePayload(array $data): array
    {
        $defaultTitle = config('app.name', 'Pelican');
        $defaultBody = trans('pwa.messages.new_notification');

        $title = $data['title'] ?? $data['subject'] ?? $defaultTitle;
        $body = $data['body'] ?? $data['message'] ?? $defaultBody;
        $url = $data['url'] ?? $data['action_url'] ?? url('/app');

        $icon = asset(ltrim($this->settings->get('default_notification_icon', '/pelican.svg'), '/'));
        $badge = asset(ltrim($this->settings->get('default_notification_badge', '/pelican.svg'), '/'));

        return [
            'title' => $title,
            'body' => $body,
            'icon' => $data['icon'] ?? $icon,
            'badge' => $data['badge'] ?? $badge,
            'url' => $url,
            'tag' => $data['tag'] ?? null,
            'requireInteraction' => !empty($data['require_interaction']),
            'actions' => $data['actions'] ?? [],
        ];
    }
}
