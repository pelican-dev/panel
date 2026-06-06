<?php

namespace App\Livewire;

use App\Enums\WebhookScope;
use App\Models\WebhookConfiguration;
use Filament\Schemas\Components\Concerns\CanPoll;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class DiscordPreview extends Component
{
    use CanPoll;
    use EvaluatesClosures;

    public ?WebhookConfiguration $record = null;

    /** @var array<string, mixed>|null */
    public ?array $formPayload = null;

    #[On('discord-form-changed')]
    public function onFormChanged(
        string $content = '',
        string $username = '',
        string $avatar_url = '',
        mixed $embeds = [],
    ): void {
        $this->formPayload = [
            'content' => $content,
            'username' => $username,
            'avatar_url' => $avatar_url,
            'embeds' => is_array($embeds) ? $embeds : [],
        ];
    }

    public function render(): View
    {
        return view('livewire.discord-preview', $this->getViewData());
    }

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
        $default = [
            'link' => fn ($href, $child) => $href ? "<a href=\"$href\" target=\"_blank\" class=\"link\">$child</a>" : $child,
            'content' => null,
            'sender' => [
                'name' => 'Pelican',
                'avatar' => 'https://raw.githubusercontent.com/pelican-dev/panel/main/public/pelican.svg',
            ],
            'embeds' => [],
            'getTime' => fn () => now()->format('H:i'),
        ];

        $payloadArray = $this->formPayload;

        if ($payloadArray === null) {
            if (!$this->record || !$this->record->payload) {
                return $default;
            }
            $payloadArray = $this->record->payload;
        }

        $scope = $this->record !== null ? $this->record->scope : WebhookScope::GLOBAL;
        $sampleData = $scope === WebhookScope::SERVER
            ? WebhookConfiguration::getServerWebhookSampleData()
            : WebhookConfiguration::getWebhookSampleData();

        $payloadJson = json_encode($payloadArray) ?: '{}';
        $replacedPayload = (new WebhookConfiguration())->replaceVars($sampleData, $payloadJson);
        $data = json_decode($replacedPayload, true) ?? [];

        return [
            'link' => fn ($href, $child) => $href ? "<a href=\"$href\" target=\"_blank\" class=\"link\">$child</a>" : $child,
            'content' => data_get($data, 'content'),
            'sender' => [
                'name' => filled(data_get($data, 'username')) ? data_get($data, 'username') : 'Pelican',
                'avatar' => filled(data_get($data, 'avatar_url')) ? data_get($data, 'avatar_url') : 'https://raw.githubusercontent.com/pelican-dev/panel/main/public/pelican.svg',
            ],
            'embeds' => collect(data_get($data, 'embeds', []))
                ->filter(fn (mixed $embed) => is_array($embed))
                ->take(10)
                ->map(function (array $embed): array {
                    $color = $embed['color'] ?? null;
                    if (is_int($color)) {
                        $embed['color'] = '#' . str_pad(dechex($color), 6, '0', STR_PAD_LEFT);
                    } elseif (!is_string($color)) {
                        $embed['color'] = null;
                    }

                    if (!isset($embed['timestamp']) && !empty($embed['has_timestamp'])) {
                        $embed['timestamp'] = now()->toIso8601String();
                    }

                    if (isset($embed['timestamp'])) {
                        try {
                            $embed['timestamp'] = Carbon::parse($embed['timestamp'])->format('M j, Y H:i');
                        } catch (\Throwable) {
                            unset($embed['timestamp']);
                        }
                    }

                    return $embed;
                })
                ->values()
                ->all(),
            'getTime' => fn () => now()->format('H:i'),
        ];
    }
}
