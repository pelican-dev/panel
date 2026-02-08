<?php

namespace App\Http\Controllers;

use App\Models\PwaPushSubscription;
use App\Services\Pwa\PwaPushService;
use App\Services\Pwa\PwaSettingsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PwaPushController extends Controller
{
    public function __construct(
        private PwaSettingsRepository $settings,
    ) {}

    public function subscribe(Request $request): JsonResponse
    {
        $request->validate([
            'endpoint' => ['required', 'string'],
            'keys.p256dh' => ['required', 'string'],
            'keys.auth' => ['required', 'string'],
            'contentEncoding' => ['nullable', 'string'],
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => trans('pwa.errors.unauthorized'),
            ], 401);
        }

        $subscription = PwaPushSubscription::query()->updateOrCreate(
            ['endpoint' => $request->string('endpoint')->toString()],
            [
                'notifiable_type' => $user->getMorphClass(),
                'notifiable_id' => $user->getKey(),
                'public_key' => $request->input('keys.p256dh'),
                'auth_token' => $request->input('keys.auth'),
                'content_encoding' => $request->input('contentEncoding', 'aesgcm'),
                'user_agent' => $request->userAgent(),
            ]
        );

        return response()->json([
            'message' => trans('pwa.notifications.subscribed'),
            'id' => $subscription->getKey(),
        ]);
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        $request->validate([
            'endpoint' => ['required', 'string'],
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => trans('pwa.errors.unauthorized'),
            ], 401);
        }

        PwaPushSubscription::query()
            ->where('endpoint', $request->string('endpoint')->toString())
            ->where('notifiable_type', $user->getMorphClass())
            ->where('notifiable_id', $user->getKey())
            ->delete();

        return response()->json([
            'message' => trans('pwa.notifications.unsubscribed'),
        ]);
    }

    public function test(Request $request, PwaPushService $push): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => trans('pwa.errors.unauthorized'),
            ], 401);
        }

        $vapid = [
            'subject' => $this->settings->get('vapid_subject', ''),
            'publicKey' => $this->settings->get('vapid_public_key', ''),
            'privateKey' => $this->settings->get('vapid_private_key', ''),
        ];

        if (!$vapid['publicKey'] || !$vapid['privateKey']) {
            return response()->json([
                'message' => trans('pwa.errors.vapid_missing'),
            ], 400);
        }

        $subscription = PwaPushSubscription::query()
            ->where('notifiable_type', $user->getMorphClass())
            ->where('notifiable_id', $user->getKey())
            ->latest('id')
            ->first();

        if (!$subscription) {
            return response()->json([
                'message' => trans('pwa.errors.no_subscription'),
            ], 404);
        }

        $appName = config('app.name', 'Pelican');
        $icon = asset(ltrim($this->settings->get('default_notification_icon', '/pelican.svg'), '/'));
        $badge = asset(ltrim($this->settings->get('default_notification_badge', '/pelican.svg'), '/'));

        $payload = [
            'title' => $appName,
            'body' => trans('pwa.messages.test_notification_body'),
            'icon' => $icon,
            'badge' => $badge,
            'url' => url('/'),
            'tag' => 'pwa-test',
        ];

        $result = $push->sendToSubscription($subscription, $payload, $vapid);

        return response()->json([
            'message' => $result === true
                ? trans('pwa.notifications.test_sent')
                : trans('pwa.errors.send_failed') . ': ' . $result,
        ], $result === true ? 200 : 500);
    }
}
