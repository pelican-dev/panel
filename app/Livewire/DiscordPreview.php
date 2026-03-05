<?php

namespace App\Livewire;

use App\Enums\WebhookScope;
use App\Models\WebhookConfiguration;
use Filament\Schemas\Components\Concerns\CanPoll;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Livewire\Component;

class DiscordPreview extends Component
{
    use CanPoll;
    use EvaluatesClosures;

    public ?WebhookConfiguration $record = null;

    /** @var string|array<string, mixed>|null */
    public string|array|null $payload = null;

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
        if (!$this->record || !$this->record->payload) {
            return [
                'link' => fn ($href, $child) => $href ? "<a href=\"$href\" target=\"_blank\" class=\"link\">$child</a>" : $child,
                'content' => null,
                'sender' => [
                    'name' => 'Pelican',
                    'avatar' => 'https://raw.githubusercontent.com/pelican-dev/panel/main/public/pelican.svg',
                ],
                'embeds' => [],
                'getTime' => fn () => now()->format('H:i'),
            ];
        }

        $this->payload = json_encode($this->record->payload);

        $sampleData = $this->record->scope === WebhookScope::SERVER
            ? WebhookConfiguration::getServerWebhookSampleData()
            : WebhookConfiguration::getWebhookSampleData();

        $replacedPayload = $this->record->replaceVars($sampleData, $this->payload);
        $data = json_decode($replacedPayload, true);

        return [
            'link' => fn ($href, $child) => $href ? "<a href=\"$href\" target=\"_blank\" class=\"link\">$child</a>" : $child,
            'content' => data_get($data, 'content'),
            'sender' => [
                'name' => data_get($data, 'username', 'Pelican'),
                'avatar' => data_get($data, 'avatar_url', 'https://raw.githubusercontent.com/pelican-dev/panel/main/public/pelican.svg'),
            ],
            'embeds' => collect(data_get($data, 'embeds', []))
                ->take(10)
                ->map(function (array $embed): array {
                    $embed['color'] = $embed['color'] ?? null;

                    if ($embed['color']) {
                        $embed['color'] = '#' . str_pad(dechex($embed['color']), 6, '0', STR_PAD_LEFT);
                    }

                    if (isset($embed['timestamp'])) {
                        $embed['timestamp'] = Carbon::parse($embed['timestamp'])->format('Y-m-d H:i');
                    }

                    return $embed;
                })
                ->all(),
            'getTime' => fn () => now()->format('H:i'),
        ];
    }
}
