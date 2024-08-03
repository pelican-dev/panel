<?php

namespace App\Listeners\Webhook;

use App\Events\Egg as EggEvents;
use App\Traits\SendWebhook;
use Carbon\Carbon;

class EggWebhookListener
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
    public function handle(object $event): void
    {
        if ($event instanceof EggEvents\Created) {
            $this->handleEggCreated($event);
        } elseif ($event instanceof EggEvents\Deleted) {
            $this->handleEggDeleted($event);
        }
    }

    protected function handleEggCreated($event)
    {
        $settings = $this->getEggSettings();
        $ID = $event->egg->id;
        $admin = auth()->check() ? auth()->user()->username : 'Unknown';
        $appName = env('APP_NAME');
        $APP_URL = env('APP_URL');
        $Url = "$APP_URL/admin/eggs/$ID/edit";
        $currentTime = Carbon::now()->toDateTimeString();
        $extra = "\nAdded by: $admin";

        $data = $this->getEggData($event);

        $message = [];
        foreach ($settings as $key => $isEnabled) {
            if ($isEnabled && array_key_exists($key, $data)) {
                $message[ucwords(str_replace('_', ' ', $key))] = $data[$key];
            }
        }
        $message['Added by'] = $admin;

        if (env('WEBHOOK_TYPE') === 'json') {
            $this->send(
                'egg',
                [
                    'event' => 'Egg Added',
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
                    'title' => 'Egg Added',
                    'description' => $this->buildDiscordDescription($settings, $data, $extra),
                    'footer' => [
                        'text' => "Current Time: $currentTime",
                    ],
                ],
            ];

            $this->send('egg', [
                'embeds' => $embed,
            ]);
        }
    }

    protected function handleEggDeleted($event)
    {
        $settings = $this->getEggSettings();
        $admin = auth()->check() ? auth()->user()->username : 'Unknown';
        $appName = env('APP_NAME');
        $Url = env('APP_URL');
        $currentTime = Carbon::now()->toDateTimeString();
        $extra = "\nDeleted by: $admin";

        $data = $this->getEggData($event);

        $message = [];
        foreach ($settings as $key => $isEnabled) {
            if ($isEnabled && array_key_exists($key, $data)) {
                $message[ucwords(str_replace('_', ' ', $key))] = $data[$key];
            }
        }
        $message['Deleted by'] = $admin;

        if (env('WEBHOOK_TYPE') === 'json') {
            $this->send(
                'egg',
                [
                    'event' => 'Egg Deleted',
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
                    'title' => 'Egg Deleted',
                    'description' => $this->buildDiscordDescription($settings, $data, $extra),
                    'footer' => [
                        'text' => "Current Time: $currentTime",
                    ],
                ],
            ];

            $this->send('egg', [
                'embeds' => $embed,
            ]);
        }
    }

    private function getEggSettings(): array
    {
        return [
            'id' => env('EGG_ID', true) === true,
            'uuid' => env('EGG_UUID', false) === true,
            'author' => env('EGG_AUTHOR', true) === true,
            'name' => env('EGG_NAME', true) === true,
            'description' => env('EGG_DESCRIPTION', true) === true,
            'update_url' => env('EGG_UPDATE_URL', false) === true,
            'config_stop' => env('EGG_CONFIG_STOP', false) === true,
            'config_from' => env('EGG_CONFIG_FROM', false) === true,
            'startup' => env('EGG_STARTUP', false) === true,
            'script_container' => env('EGG_SCRIPT_CONTAINER', false) === true,
            'copy_script_from' => env('EGG_COPY_SCRIPT_FROM', false) === true,
            'script_entry' => env('EGG_SCRIPT_ENTRY', false) === true,
            'force_outgoing_ip' => env('EGG_FORCE_OUTGOING_IP', false) === true,
        ];
    }

    private function getEggData($event): array
    {
        return [
            'id' => $event->egg->id,
            'uuid' => $event->egg->uuid,
            'author' => $event->egg->author,
            'name' => $event->egg->name,
            'description' => $event->egg->description,
            'update_url' => $event->egg->update_url,
            'config_stop' => $event->egg->config_stop,
            'config_from' => $event->egg->config_from,
            'startup' => $event->egg->startup,
            'script_container' => $event->egg->script_container,
            'copy_script_from' => $event->egg->copy_script_from,
            'script_entry' => $event->egg->script_entry,
        ];
    }
}
