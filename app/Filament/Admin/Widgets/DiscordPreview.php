<?php

namespace App\Filament\Admin\Widgets;

use App\Models\WebhookConfiguration;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class DiscordPreview extends Widget
{
    protected string $view = 'filament.admin.widgets.discord-preview';

    /** @var array<string, string> */
    protected $listeners = [
        'refresh-widget' => '$refresh',
    ];

    protected static bool $isDiscovered = false; // Without this its shown on every Admin Pages

    protected int|string|array $columnSpan = 1;

    public ?WebhookConfiguration $record = null;

    /** @var string|array<string, mixed>|null */
    public string|array|null $payload = null;

    /**
     * @return array{
     *     link: callable,
     *     content: mixed,
     *     sender: array{name: string, avatar: string},
     *     embeds: array<int, mixed>,
     *     getTime: mixed
     * }
     */
    public function getViewData(): array
    {
        if (!$this->record || !$this->record->payload) {
            return [
                'link' => fn ($href, $child) => $href ? "<a href=\"$href\" target=\"_blank\" class=\"link\">$child</a>" : $child,
                'content' => null,
                'sender' => [
                    'name' => 'Pelican',
                    'avatar' => 'https://raw.githubusercontent.com/pelican-dev/panel/refs/heads/main/public/pelican.ico',
                ],
                'embeds' => [],
                'getTime' => 'Today at ' . Carbon::now()->format('h:i A'),
            ];
        }

        $data = $this->getWebhookSampleData();

        if (is_string($this->record->payload)) {
            $payload = $this->replaceVarsInStringPayload($this->record->payload, $data);
        } else {
            $payload = $this->replaceVarsInArrayPayload($this->record->payload, $data);
        }

        $embeds = data_get($payload, 'embeds', []);
        foreach ($embeds as &$embed) {
            if (data_get($embed, 'has_timestamp')) {
                unset($embed['has_timestamp']);
                $embed['timestamp'] = 'Today at ' . Carbon::now()->format('h:i A');
            }
        }

        return [
            'link' => fn ($href, $child) => $href ? sprintf('<a href="%s" target="_blank" class="link">%s</a>', $href, $child) : $child,
            'content' => data_get($payload, 'content'),
            'sender' => [
                'name' => data_get($payload, 'username', 'Pelican'),
                'avatar' => data_get($payload, 'avatar_url', 'https://raw.githubusercontent.com/pelican-dev/panel/refs/heads/main/public/pelican.ico'),
            ],
            'embeds' => $embeds,
            'getTime' => 'Today at ' . Carbon::now()->format('h:i A'),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function replaceVarsInStringPayload(?string $payload, array $data): ?string
    {
        if ($payload === null) {
            return null;
        }

        return preg_replace_callback('/{{\s*([\w\.]+)\s*}}/', fn ($m) => data_get($data, $m[1], $m[0]),
            $payload
        );
    }

    /**
     * @param  array<string, mixed>|null  $payload
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>|null
     */
    private function replaceVarsInArrayPayload(?array $payload, array $data): ?array
    {
        if ($payload === null) {
            return null;
        }

        foreach ($payload as $key => $value) {
            if (is_string($value)) {
                $payload[$key] = $this->replaceVarsInStringPayload($value, $data);
            } elseif (is_array($value)) {
                $payload[$key] = $this->replaceVarsInArrayPayload($value, $data);
            }
        }

        return $payload;
    }

    /**
     * @return array<string, mixed>
     */
    public function getWebhookSampleData(): array
    {
        return [
            'event' => 'updated: server',
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
