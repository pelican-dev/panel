<?php

namespace App\Listeners\Webhook;

use App\Events\DatabaseHost as DatabaseHostEvents;
use App\Traits\SendWebhook;
use Carbon\Carbon;

class DatabaseHostWebhookListener
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
        if ($event instanceof DatabaseHostEvents\Created) {
            $this->handleDatabaseHostCreated($event);
        } elseif ($event instanceof DatabaseHostEvents\Deleted) {
            $this->handleDatabaseHostDeleted($event);
        }
    }

    protected function handleDatabaseHostCreated($event)
    {
        $settings = $this->getDatabaseHostSettings();
        $ID = $event->egg->id;
        $admin = auth()->check() ? auth()->user()->username : 'Unknown';
        $appName = env('APP_NAME');
        $APP_URL = env('APP_URL');
        $Url = "$APP_URL/admin/database-hosts/$ID/edit";
        $currentTime = Carbon::now()->toDateTimeString();
        $extra = "\nAdded by: $admin";

        $data = $this->getDatabaseHostData($event);

        $message = [];
        foreach ($settings as $key => $isEnabled) {
            if ($isEnabled && array_key_exists($key, $data)) {
                $message[ucwords(str_replace('_', ' ', $key))] = $data[$key];
            }
        }
        $message['Added by'] = $admin;

        if (env('WEBHOOK_TYPE') === 'json') {
            $this->send(
                'databasehost',
                [
                    'event' => 'DatabaseHost Added',
                    'triggered_at' => $currentTime,
                    'data' => $message,
                ]
            );
        } elseif (env('WEBHOOK_TYPE') === 'discord') {
            $embed = [
                [
                    'author' => [
                        'name' => $appName,
                        'url' => $Url,
                    ],
                    'title' => 'DatabaseHost Added',
                    'description' => $this->buildDiscordDescription($settings, $data, $extra),
                    'footer' => [
                        'text' => "Current Time: $currentTime",
                    ],
                ],
            ];

            $this->send('databasehost', [
                'embeds' => $embed,
            ]);
        }
    }

    protected function handleDatabaseHostDeleted($event)
    {
        $settings = $this->getDatabaseHostSettings();
        $admin = auth()->check() ? auth()->user()->username : 'Unknown';
        $appName = env('APP_NAME');
        $Url = env('APP_URL');
        $currentTime = Carbon::now()->toDateTimeString();
        $extra = "\nDeleted by: $admin";

        $data = $this->getDatabaseHostData($event);

        $message = [];
        foreach ($settings as $key => $isEnabled) {
            if ($isEnabled && array_key_exists($key, $data)) {
                $message[ucwords(str_replace('_', ' ', $key))] = $data[$key];
            }
        }
        $message['Deleted by'] = $admin;

        if (env('WEBHOOK_TYPE') === 'json') {
            $this->send(
                'databasehost',
                [
                    'event' => 'DatabaseHost Deleted',
                    'triggered_at' => $currentTime,
                    'data' => $message,
                ]
            );
        } elseif (env('WEBHOOK_TYPE') === 'discord') {
            $embed = [
                [
                    'author' => [
                        'name' => $appName,
                        'url' => $Url,
                    ],
                    'title' => 'DatabaseHost Deleted',
                    'description' => $this->buildDiscordDescription($settings, $data, $extra),
                    'footer' => [
                        'text' => "Current Time: $currentTime",
                    ],
                ],
            ];

            $this->send('databasehost', [
                'embeds' => $embed,
            ]);
        }
    }

    private function getDatabaseHostSettings(): array
    {
        return [
            'id' => env('DATABASE_HOST_ID', true) === true,
            'name' => env('DATABASE_HOST_NAME', true) === true,
            'host' => env('DATABASE_HOST_HOST', true) === true,
            'port' => env('DATABASE_HOST_PORT', true) === true,
            'max_databases' => env('DATABASE_HOST_MAX_DB', true) === true,
            'node_id' => env('DATABASE_HOST_NODE_ID', true) === true,
        ];
    }

    private function getDatabaseHostData($event): array
    {
        return [
            'id' => $event->databaseHost->id,
            'name' => $event->databaseHost->name,
            'host' => $event->databaseHost->host,
            'port' => $event->databaseHost->port,
            'max_databases' => $event->databaseHost->max_databases,
            'node_id' => $event->databaseHost->node_id,
        ];
    }
}
