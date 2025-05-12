<?php

namespace App\Filament\Admin\Widgets;

use App\Models\WebhookConfiguration;
use Filament\Widgets\Widget;

class DiscordPreview extends Widget
{
    protected static string $view = 'filament.admin.widgets.discord-preview';

    protected $listeners = [
        'refresh-widget' => '$refresh',
    ];

    protected static bool $isDiscovered = false;
    protected int|string|array $columnSpan = 1;
    public ?WebhookConfiguration $record = null;

    public function getViewData(): array
    {
        if (!$this->record || !$this->record->payload) {
            return [
                'link' => fn ($href, $child) => $href ? sprintf('<a href="%s" target="_blank" class="link">%s</a>', $href, $child) : $child,
                'content' => null,
                'sender' => [
                    'name' => 'Pelican',
                    'avatar' => 'https://cdn.discordapp.com/avatars/1222179499253170307/d4d6873acc8a0d5fb5eaa5aa81572cf3.png',
                ],
                'embeds' => [],
                'getTime' => WebhookConfiguration::getTime(),
            ];
        }

        $data = $this->getSampleData();

        $payload = $this->replaceVarsInPayload($this->record->payload ?? [], $data);

        $embeds = data_get($payload, 'embeds', []);
        foreach ($embeds as &$embed) {
            if (data_get($embed, 'has_timestamp')) {
                unset($embed['has_timestamp']);
                $embed['timestamp'] = $this->record->getTime();
            }
        }

        return [
            'link' => fn ($href, $child) => $href ? sprintf('<a href="%s" target="_blank" class="link">%s</a>', $href, $child) : $child,
            'content' => data_get($payload, 'content'),
            'sender' => [
                'name' => data_get($payload, 'username', 'Pelican'),
                'avatar' => data_get($payload, 'avatar_url', 'https://cdn.discordapp.com/avatars/1222179499253170307/d4d6873acc8a0d5fb5eaa5aa81572cf3.png'),
            ],
            'embeds' => $embeds,
            'getTime' => $this->record->getTime(),
        ];
    }

    private function replaceVarsInPayload(array|string $payload, array $data): array|string
    {
        if (is_string($payload)) {
            return preg_replace_callback('/{{\s*([\w\.]+)\s*}}/', function ($matches) use ($data) {
                $keys = explode('.', $matches[1]);
                $value = $data;
                foreach ($keys as $key) {
                    if (is_array($value) && array_key_exists($key, $value)) {
                        $value = $value[$key];
                    } else {
                        return $matches[0];
                    }
                }
                return $value;
            }, $payload);
        }

        foreach ($payload as $key => $value) {
            $payload[$key] = $this->replaceVarsInPayload($value, $data);
        }
        return $payload;
    }
        private function getSampleData(): array
    {
        return [
            'id' => 2,
            'external_id' => 10,
            'uuid' => '651fgbc1-dee6-4250-814e-10slda13f1e',
            'uuid_short' => '651fgbc1',
            'node_id' => 1,
            'name' => 'Example Server',
            'description' => 'This is an example server description.',
            'status' => 'running',
            'skip_scripts' => false,
            'owner_id' => 1,
            'memory' => 512,
            'swap' => 128,
            'disk' => 10240,
            'io' => 500,
            'cpu' => 500,
            'threads' => '1, 3, 5',
            'oom_killer' => false,
            'allocation_id' => 4,
            'egg_id' => 2,
            'startup' => 'This is a example startup command.',
            'image' => 'Image here',
            'allocation_limit' => 5,
            'database_limit' => 1,
            'backup_limit' => 3,
            'created_at' => '2025-03-17T15:20:32.000000Z',
            'updated_at' => '2025-05-12T17:53:12.000000Z',
            'installed_at' => '2025-04-27T21:06:01.000000Z',
            'docker_labels' => [],
            'allocation' => [
                'id' => 4,
                'node_id' => 1,
                'ip' => '192.168.0.3',
                'ip_alias' => null,
                'port' => 25567,
                'server_id' => 2,
                'notes' => null,
                'created_at' => '2025-03-17T15:20:09.000000Z',
                'updated_at' => '2025-03-17T15:20:32.000000Z',
            ],
        ];
    }
}