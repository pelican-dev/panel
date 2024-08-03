<?php

namespace App\Listeners\Webhook;

use App\Events\Server as ServerEvents;
use App\Traits\SendWebhook;
use Carbon\Carbon;

class ServerWebhookListener
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
        if ($event instanceof ServerEvents\Created) {
            $this->handleServerCreated($event);
        } elseif ($event instanceof ServerEvents\Deleted) {
            $this->handleServerDeleted($event);
        }
    }

    protected function handleServerCreated($event)
    {
        $settings = $this->getServerSettings();
        $ID = $event->server->id;
        $admin = auth()->check() ? auth()->user()->username : 'Unknown';
        $appName = env('APP_NAME');
        $APP_URL = env('APP_URL');
        $Url = "$APP_URL/admin/servers/$ID/edit";
        $currentTime = Carbon::now()->toDateTimeString();
        $extra = "\nCreated by: $admin";

        $data = $this->getServerData($event);

        $message = [];
        foreach ($settings as $key => $isEnabled) {
            if ($isEnabled && array_key_exists($key, $data)) {
                $message[ucwords(str_replace('_', ' ', $key))] = $data[$key];
            }
        }
        $message['Created by'] = $admin;

        if (env('WEBHOOK_TYPE') === 'json') {
            $this->send(
                'server',
                [
                    'event' => 'Server Created',
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
                    'title' => 'Server Created',
                    'description' => $this->buildDiscordDescription($settings, $data, $extra),
                    'footer' => [
                        'text' => "Current Time: $currentTime",
                    ],
                ],
            ];

            $this->send('server', [
                'embeds' => $embed,
            ]);
        }
    }

    protected function handleServerDeleted($event)
    {
        $settings = $this->getServerSettings();
        $admin = auth()->check() ? auth()->user()->username : 'Unknown';
        $appName = env('APP_NAME');
        $APP_URL = env('APP_URL');
        $currentTime = Carbon::now()->toDateTimeString();
        $extra = "\nDeleted by: $admin";

        $data = $this->getServerData($event);

        $message = [];
        foreach ($settings as $key => $isEnabled) {
            if ($isEnabled && array_key_exists($key, $data)) {
                $message[ucwords(str_replace('_', ' ', $key))] = $data[$key];
            }
        }
        $message['Deleted by'] = $admin;

        if (env('WEBHOOK_TYPE') === 'json') {
            $this->send(
                'server',
                [
                    'event' => 'Server Deleted',
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
                        'url' => $APP_URL,
                    ],
                    'title' => 'Server Deleted',
                    'description' => $this->buildDiscordDescription($settings, $data, $extra),
                    'footer' => [
                        'text' => "Current Time: $currentTime",
                    ],
                ],
            ];

            $this->send('server', [
                'embeds' => $embed,
            ]);
        }
    }

    private function getServerSettings(): array
    {
        return [
            'id' => env('SERVER_ID', true) === true,
            'external_id' => env('SERVER_EXTERNAL_ID', false) === true,
            'uuid' => env('SERVER_UUID', false) === true,
            'node_id' => env('SERVER_NODE_ID', true) === true,
            'name' => env('SERVER_NAME', true) === true,
            'description' => env('SERVER_DESCRIPTION', true) === true,
            'owner_id' => env('SERVER_OWNER_ID', true) === true,
            'memory' => env('SERVER_MEMORY', false) === true,
            'disk' => env('SERVER_DISK', false) === true,
            'cpu' => env('SERVER_CPU', false) === true,
            'egg_id' => env('SERVER_CPU', true) === true,
            'startup' => env('SERVER_STARTUP', false) === true,
            'allocation_limit' => env('SERVER_ALLO_LIMIT', false) === true,
            'database_limit' => env('SERVER_DB_LIMIT', false) === true,
            'backup_limit' => env('SERVER_BACKUP_LIMIT', false) === true,
        ];
    }

    private function getServerData($event): array
    {
        return [
            'id' => $event->server->id,
            'external_id' => $event->server->external_id ?? 'Unknown',
            'uuid' => $event->server->uuid,
            'node_id' => $event->server->node_id,
            'name' => $event->server->name,
            'description' => $event->server->description,
            'owner_id' => $event->server->owner_id,
            'memory' => $event->server->memory,
            'disk' => $event->server->disk,
            'cpu' => $event->server->cpu,
            'egg_id' => $event->server->egg_id,
            'startup' => $event->server->startup,
            'allocation_limit' => $event->server->allocation_limit,
            'database_limit' => $event->server->database_limit,
            'backup_limit' => $event->server->backup_limit,
        ];
    }
}
