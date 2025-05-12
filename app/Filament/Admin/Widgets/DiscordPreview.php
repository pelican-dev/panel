<?php

namespace App\Filament\Admin\Widgets;

use App\Models\WebhookConfiguration;
use Filament\Widgets\Widget;

class DiscordPreview extends Widget
{
    protected static string $view = 'filament.admin.widgets.discord-preview';

    //Don't invert it, if used normal one will reload the faker every time you write on form.
    /** @var array<string, string> */
    protected $listeners = [
        'widget-refresh' => '$refresh',
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
                'sender' => [
                    'name' => 'Pelican',
                    'avatar' => 'https://cdn.discordapp.com/avatars/1222179499253170307/d4d6873acc8a0d5fb5eaa5aa81572cf3.png',
                ],
                'embeds' => [],
                'getTime' => WebhookConfiguration::getTime(),
            ];
        }
        // TODO: Change processPayload to json_encode, as this is a temporal fix
        $data = $this->record->run(true);
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
            'sender' => [
                'name' => data_get($payload, 'username', 'Pelican'),
                'avatar' => data_get($payload, 'avatar_url', 'https://cdn.discordapp.com/avatars/1222179499253170307/d4d6873acc8a0d5fb5eaa5aa81572cf3.png'),
            ],
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

        if (is_string($payload)) {
            return $this->record->replaceVars($data, $payload);
        }

        foreach ($payload as $key => $value) {
            if (is_array($value) || is_string($value)) {
                $payload[$key] = $this->processPayload($value, $data);
            } else {
              $payload[$key] = $value;
            }
        }

        return $payload;
    }
}
