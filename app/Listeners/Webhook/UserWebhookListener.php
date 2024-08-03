<?php

namespace App\Listeners\Webhook;

use App\Events\User as UserEvents;
use App\Traits\SendWebhook;
use Carbon\Carbon;

class UserWebhookListener
{
    use SendWebhook;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle($event)
    {
        if ($event instanceof UserEvents\Created) {
            $this->handleUserCreated($event);
        } elseif ($event instanceof UserEvents\Deleted) {
            $this->handleUserDeleted($event);
        }
    }

    protected function handleUserCreated($event)
    {
        $settings = $this->getUserSettings();
        $appName = env('APP_NAME');
        $Url = env('APP_URL');
        $currentTime = Carbon::now()->toDateTimeString();
        $admin = auth()->check() ? auth()->user()->username : 'Unknown';
        $extra = "\nCreated by: $admin";

        $data = $this->getUserData($event);

        $message = [];
        foreach ($settings as $key => $isEnabled) {
            if ($isEnabled && array_key_exists($key, $data)) {
                $message[ucwords(str_replace('_', ' ', $key))] = $data[$key];
            }
        }
        $message['Created by'] = $admin;

        if (env('WEBHOOK_TYPE') === 'json') {
            $this->send(
                'user',
                [
                    'event' => 'User Created',
                    'triggered_at' => $currentTime,
                    'data' => $message,
                ]
            );
        } elseif (env('WEBHOOK_TYPE') === 'discord') {
            $embed = [
                [
                    'author' => [
                        'name' => $appName,
                        'icon_url' => 'https://pelican.dev/img/logo.png',
                        'url' => $Url,
                    ],
                    'title' => 'User Created',
                    'description' => $this->buildDiscordDescription($settings, $data, $extra),
                    'footer' => [
                        'text' => "Current Time: $currentTime",
                    ],
                ],
            ];

            $this->send('user', [
                'embeds' => $embed,
            ]);
        }
    }

    protected function handleUserDeleted($event)
    {
        $settings = $this->getUserSettings();
        $appName = env('APP_NAME');
        $Url = env('APP_URL');
        $currentTime = Carbon::now()->toDateTimeString();
        $admin = auth()->check() ? auth()->user()->username : 'Unknown';
        $extra = "\nDeleted by: $admin";

        $data = $this->getUserData($event);

        $message = [];
        foreach ($settings as $key => $isEnabled) {
            if ($isEnabled && array_key_exists($key, $data)) {
                $message[ucwords(str_replace('_', ' ', $key))] = $data[$key];
            }
        }
        $message['Deleted by'] = $admin;

        if (env('WEBHOOK_TYPE') === 'json') {
            $this->send(
                'user',
                [
                    'event' => 'User Deleted',
                    'triggered_at' => $currentTime,
                    'data' => $message,
                ]
            );
        } elseif (env('WEBHOOK_TYPE') === 'discord') {
            $embed = [
                [
                    'author' => [
                        'name' => $appName,
                        'icon_url' => 'https://pelican.dev/img/logo.png',
                        'url' => $Url,
                    ],
                    'title' => 'User Deleted',
                    'description' => $this->buildDiscordDescription($settings, $data, $extra),
                    'footer' => [
                        'text' => "Current Time: $currentTime",
                    ],
                ],
            ];

            $this->send('user', [
                'embeds' => $embed,
            ]);
        }
    }

    private function getUserSettings(): array
    {
        return [
            'id' => env('USER_ID', true) === true,
            'external_id' => env('USER_EXTERNAL_ID', false) === true,
            'uuid' => env('USER_UUID', false) === true,
            'username' => env('USER_USERNAME', true) === true,
            'email' => env('USER_EMAIL', true) === true,
            'language' => env('USER_LANG', false) === true,
            'timezone' => env('USER_TIME', false) === true,
        ];
    }

    private function getUserData($event): array
    {
        return [
            'id' => $event->user->id,
            'external_id' => $event->user->external_id ?? 'Unknown',
            'uuid' => $event->user->uuid,
            'username' => $event->user->username,
            'email' => $event->user->email,
            'language' => $event->user->language ?? 'Unknown',
            'timezone' => $event->user->timezone ?? 'Unknown',
        ];
    }
}
