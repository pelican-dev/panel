<?php

namespace App\Filament\Admin\Resources\WebhookResource\Pages;

use App\Filament\Admin\Resources\WebhookResource;
use App\Models\WebhookConfiguration;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Widgets\WidgetConfiguration;

class EditWebhookConfiguration extends EditRecord
{
    protected static string $resource = WebhookResource::class;

    /**
     * @return array<WidgetConfiguration>
     */
    protected function getHeaderWidgets(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Action::make('runNow')
                ->label('Run now')
                ->color('primary')
                ->disabled(fn (WebhookConfiguration $webhookConfiguration) => count($webhookConfiguration->events) === 0)
                ->action(fn (WebhookConfiguration $webhookConfiguration) => $webhookConfiguration->run()),
            $this->getSaveFormAction()->formId('form'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['type'] === 'discord') {
            $embeds = data_get($data, 'embeds', []);

            foreach ($embeds as &$embed) {
                $embed['color'] = hexdec(str_replace('#', '', data_get($embed, 'color')));
                $embed = collect($embed)->filter(fn ($key) => is_array($key) ? array_filter($key, fn ($arr_key) => !empty($arr_key)) : !empty($key))->all();
            }

            $flags = collect(data_get($data, 'flags'))->reduce(fn ($carry, $bit) => $carry | $bit, 0);

            $tmp = collect([
                'username' => data_get($data, 'username'),
                'avatar_url' => data_get($data, 'avatar_url'),
                'content' => data_get($data, 'content'),
                'image' => data_get($data, 'image'),
                'thumbnail' => data_get($data, 'thumbnail'),
                'embeds' => $embeds,
                'thread_name' => data_get($data, 'thread_name'),
                'flags' => $flags,
                'allowed_mentions' => data_get($data, 'allowed_mentions', []),
            ])->filter(fn ($key) => !empty($key))->all();

            unset($data['username'], $data['avatar_url'], $data['content'], $data['image'], $data['thumbnail'], $data['embeds'], $data['thread_name'], $data['flags'], $data['allowed_mentions']);

            $data['payload'] = $tmp;
        }

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($data['type'] === 'discord') {
            $embeds = data_get($data, 'payload.embeds', []);

            foreach ($embeds as &$embed) {
                $embed['color'] = '#' . dechex(data_get($embed, 'color'));
                $embed = collect($embed)->filter(fn ($key) => is_array($key) ? array_filter($key, fn ($arr_key) => !empty($arr_key)) : !empty($key))->all();
            }

            $flags = data_get($data, 'payload.flags');
            $flags = collect(range(0, PHP_INT_SIZE * 8 - 1))
                ->filter(fn ($i) => ($flags & (1 << $i)) !== 0)
                ->map(fn ($i) => 1 << $i)
                ->values();

            $tmp = collect([
                'username' => data_get($data, 'payload.username'),
                'avatar_url' => data_get($data, 'payload.avatar_url'),
                'content' => data_get($data, 'payload.content'),
                'image' => data_get($data, 'payload.image'),
                'thumbnail' => data_get($data, 'payload.thumbnail'),
                'embeds' => $embeds,
                'thread_name' => data_get($data, 'payload.thread_name'),
                'flags' => $flags,
                'allowed_mentions' => data_get($data, 'payload.allowed_mentions'),
            ])->filter(fn ($key) => !empty($key))->all();

            unset($data['payload'], $data['created_at'], $data['updated_at'], $data['deleted_at']);
            $data = array_merge($data, $tmp);
        }

        return $data;
    }
    
    protected function afterSave(): void
    {
        $this->dispatch('widget-refresh');
    }
}
