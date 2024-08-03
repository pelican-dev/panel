<?php

namespace App\Listeners\Webhook;

use App\Events\Node as NodeEvents;
use App\Traits\SendWebhook;
use Carbon\Carbon;

class NodeWebhookListener
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
        if ($event instanceof NodeEvents\Created) {
            $this->handleNodeCreated($event);
        } elseif ($event instanceof NodeEvents\Deleted) {
            $this->handleNodeDeleted($event);
        }
    }

    protected function handleNodeCreated($event)
    {
        $settings = $this->getNodeSettings();
        $appName = env('APP_NAME');
        $Url = env('APP_URL');
        $currentTime = Carbon::now()->toDateTimeString();
        $admin = auth()->check() ? auth()->user()->username : 'Unknown';
        $extra = "\nCreated by: $admin";

        $data = $this->getNodeData($event);

        $message = [];
        foreach ($settings as $key => $isEnabled) {
            if ($isEnabled && array_key_exists($key, $data)) {
                $message[ucwords(str_replace('_', ' ', $key))] = $data[$key];
            }
        }
        $message['Created by'] = $admin;

        if (env('WEBHOOK_TYPE') === 'json') {
            $this->send(
                'node',
                [
                    'event' => 'Node Created',
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
                    'title' => 'Node Created',
                    'description' => $this->buildDiscordDescription($settings, $data, $extra),
                    'footer' => [
                        'text' => "Current Time: $currentTime",
                    ],
                ],
            ];

            $this->send('node', [
                'embeds' => $embed,
            ]);
        }
    }

    protected function handleNodeDeleted($event)
    {
        $settings = $this->getNodeSettings();
        $appName = env('APP_NAME');
        $Url = env('APP_URL');
        $currentTime = Carbon::now()->toDateTimeString();
        $admin = auth()->check() ? auth()->user()->username : 'Unknown';
        $extra = "\nDeleted by: $admin";

        $data = $this->getNodeData($event);

        $message = [];
        foreach ($settings as $key => $isEnabled) {
            if ($isEnabled && array_key_exists($key, $data)) {
                $message[ucwords(str_replace('_', ' ', $key))] = $data[$key];
            }
        }
        $message['Deleted by'] = $admin;

        if (env('WEBHOOK_TYPE') === 'json') {
            $this->send(
                'node',
                [
                    'event' => 'Node Deleted',
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
                    'title' => 'Node Deleted',
                    'description' => $this->buildDiscordDescription($settings, $data, $extra),
                    'footer' => [
                        'text' => "Current Time: $currentTime",
                    ],
                ],
            ];

            $this->send('node', [
                'embeds' => $embed,
            ]);
        }
    }

    private function getNodeSettings(): array
    {
        return [
            'id' => env('NODE_ID', true) === true,
            'uuid' => env('NODE_UUID', false) === true,
            'public' => env('NODE_PUBLIC', false) === true,
            'name' => env('NODE_NAME', true) === true,
            'description' => env('NODE_DESCRIPTION', true) === true,
            'location_id' => env('NODE_LOCATION_ID', true) === true,
            'fqdn' => env('NODE_FQDN', true) === true,
            'scheme' => env('NODE_SCHEME', true) === true,
            'behind_proxy' => env('NODE_BEHIND_PROXY', false) === true,
            'memory' => env('NODE_MEMORY', true) === true,
            'memory_overallocate' => env('NODE_MEMORY_OVER', false) === true,
            'disk' => env('NODE_DISK', true) === true,
            'disk_overallocate' => env('NODE_DISK_OVER', false) === true,
            'cpu' => env('NODE_CPU', true) === true,
            'cpu_overallocate' => env('NODE_CPU_OVER', false) === true,
        ];
    }

    private function getNodeData($event): array
    {
        return [
            'id' => $event->node->id,
            'uuid' => $event->node->uuid,
            'public' => $event->node->public,
            'name' => $event->node->name,
            'description' => $event->node->description,
            'location_id' => $event->node->location_id,
            'fqdn' => $event->node->fqdn,
            'scheme' => $event->node->scheme,
            'behind_proxy' => $event->node->behind_proxy,
            'memory' => $event->node->memory,
            'memory_overallocate' => $event->node->memory_overallocate,
            'disk' => $event->node->disk,
            'disk_overallocate' => $event->node->disk_overallocate,
            'cpu' => $event->node->cpu,
            'cpu_overallocate' => $event->node->cpu_overallocate,
        ];
    }
}
