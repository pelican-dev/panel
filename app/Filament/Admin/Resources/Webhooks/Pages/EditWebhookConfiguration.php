<?php

namespace App\Filament\Admin\Resources\Webhooks\Pages;

use App\Enums\WebhookType;
use App\Filament\Admin\Resources\Webhooks\WebhookResource;
use App\Models\WebhookConfiguration;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\IconSize;

class EditWebhookConfiguration extends EditRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = WebhookResource::class;

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->iconButton()->iconSize(IconSize::ExtraLarge),
            Action::make('test_now')
                ->label(trans('admin/webhook.test_now'))
                ->color('primary')
                ->disabled(fn (WebhookConfiguration $webhookConfiguration) => count($webhookConfiguration->events) === 0)
                ->action(fn (WebhookConfiguration $webhookConfiguration) => $webhookConfiguration->run())
                ->tooltip(trans('admin/webhook.test_now_help')),
            $this->getSaveFormAction()->formId('form')
                ->iconButton()->iconSize(IconSize::ExtraLarge)
                ->icon('tabler-device-floppy'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['type'] ?? null) === WebhookType::Discord) {
            $embeds = data_get($data, 'embeds', []);

            foreach ($embeds as &$embed) {
                $embed['color'] = hexdec(str_replace('#', '', data_get($embed, 'color')));
                $embed = collect($embed)->filter(fn ($key) => is_array($key) ? array_filter($key, fn ($arr_key) => !empty($arr_key)) : !empty($key))->all();
            }

            $flags = collect($data['flags'] ?? [])->reduce(fn ($carry, $bit) => $carry | $bit, 0);

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

        if (($data['type'] ?? null) === WebhookType::Regular && isset($data['headers'])) {
            $newHeaders = [];
            foreach ($data['headers'] as $key => $value) {
                $newKey = str_replace(' ', '-', $key);
                $newHeaders[$newKey] = $value;
            }
            $data['headers'] = $newHeaders;
        }

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (($data['type'] ?? null) === WebhookType::Discord->value) {
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

        if (($data['type'] ?? null) === WebhookType::Regular->value) {
            $data['headers'] = $data['headers'] ?? [];
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $this->dispatch('refresh-widget');
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);
        WebhookResource::sendHelpBanner();
    }
}
