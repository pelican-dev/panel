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
}
