<?php

namespace App\Filament\Admin\Resources\Webhooks\Pages;

use App\Enums\WebhookType;
use App\Filament\Admin\Resources\Webhooks\WebhookResource;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\IconSize;

class CreateWebhookConfiguration extends CreateRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = WebhookResource::class;

    protected static bool $canCreateAnother = false;

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            $this->getCancelFormAction()->formId('form')
                ->iconButton()->iconSize(IconSize::ExtraLarge)
                ->icon('tabler-arrow-left'),
            $this->getCreateFormAction()->formId('form')
                ->iconButton()->iconSize(IconSize::ExtraLarge)
                ->icon('tabler-file-plus'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (($data['type'] ?? null) === WebhookType::Discord->value) {
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

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return EditWebhookConfiguration::getUrl(['record' => $this->getRecord()]);
    }

    public function mount(): void
    {
        parent::mount();
        WebhookResource::sendHelpBanner();
    }
}
