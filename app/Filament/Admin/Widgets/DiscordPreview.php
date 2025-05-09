<?php

namespace App\Filament\Admin\Widgets;

use App\Models\WebhookConfiguration;
use Filament\Widgets\Widget;

class DiscordPreview extends Widget
{
    protected static string $view = 'filament.admin.widgets.discord-preview';

    /** @var array<string, string> */
    protected $listeners = [
        'refresh-widget' => '$refresh',
    ];

    protected static bool $isDiscovered = false; // Without this its shown on every Admin Pages

    protected int|string|array $columnSpan = 1;

    public ?WebhookConfiguration $record = null;

    public function getViewData(): array
    {
        if (!$this->record || !$this->record->payload) {
            return [
                'link' => fn ($href, $child) => $href ? sprintf('<a href="%s" target="_blank" class="link">%s</a>', $href, $child) : $child,
                'content' => null,
                'sender' => $this->easterEgg(null),
                'embeds' => [],
                'getTime' => WebhookConfiguration::getTime(),
            ];
        }

        $data = $this->record->run(true);

        // TODO: Change processPayload to json_encode, as this is a temporal fix
        $payload = $this->processPayload($this->record->payload ?? [], $data);

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
            'sender' => $this->easterEgg(data_get($payload, 'username')),
            'embeds' => $embeds,
            'getTime' => $this->record->getTime(),
        ];
    }

    /**
     * Process payload by replacing variables
     *
     * @param  array<string, mixed>|string  $payload
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>|string
     */
    private function processPayload(array|string $payload, array $data): array|string
    {
        if (!$this->record) {
            return is_array($payload) ? $payload : (string) $payload;
        }

        if (is_string($payload)) {
            return $this->record->replaceVars($data, $payload);
        }

        foreach ($payload as $key => $value) {
            $payload[$key] = is_array($value) || is_string($value)
                ? $this->processPayload($value, $data)
                : $value;
        }

        return $payload;
    }

    /** @return array<string, mixed> */
    private function easterEgg(?string $author): array
    {
        $avatar = $this->record ? data_get($this->record->payload, 'avatar_url') : null;

        // If this is approved, add the other pelican contributors.
        return match ($author) {
            'Lance' => [
                'name' => $author,
                'avatar' => 'https://cdn.discordapp.com/avatars/108350949411532800/5c0366c62ccb4263734f9decebf4944d.png',
                'decoration' => 'https://cdn.discordapp.com/avatar-decoration-presets/a_b3d5743ff7a2cda95d28fd984f82a5f8.png?size=96&amp;amp;passthrough=false',
                'human' => true,
            ],
            'notCharles' => [
                'name' => $author,
                'avatar' => 'https://cdn.discordapp.com/avatars/168955129830178816/d6de49de0ff5f3f3338c8cad510825cf.png',
                'decoration' => null,
                'human' => true,
            ],
            'JoanFo' => [
                'name' => $author,
                'avatar' => 'https://www.gravatar.com/avatar/8a50b66d9270c58d382cc3c840ec8078',
                'decoration' => 'https://cdn.discordapp.com/avatar-decoration-presets/a_af5ee420e5f860ff2cdbb5fa4633f2cf.png?size=96&amp;amp;passthrough=false',
                'human' => true,
            ],
            default => [
                'name' => $author ?? 'Pelican',
                'avatar' => $avatar ?? 'https://cdn.discordapp.com/avatars/1222179499253170307/d4d6873acc8a0d5fb5eaa5aa81572cf3.png',
                'decoration' => null,
                'human' => false,
            ]
        };
    }
}
