<?php

namespace App\Filament\Admin\Resources\Webhooks\Pages;

use App\Enums\TablerIcon;
use App\Enums\WebhookScope;
use App\Enums\WebhookType;
use App\Filament\Admin\Resources\Webhooks\WebhookResource;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

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
                ->hiddenLabel()
                ->tooltip(trans('filament-panels::resources/pages/create-record.form.actions.cancel.label'))
                ->icon(TablerIcon::ArrowLeft),
            Action::make('create')
                ->hiddenLabel()
                ->action('create')
                ->keyBindings(['mod+s'])
                ->tooltip(trans('filament-panels::resources/pages/create-record.form.actions.create.label'))
                ->icon(TablerIcon::Plus),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure name is set (required field)
        if (empty($data['name'] ?? null)) {
            throw ValidationException::withMessages(['name' => 'Webhook name is required']);
        }

        // Set scope to GLOBAL by default for admin webhooks
        $data['scope'] = WebhookScope::Global;
        unset($data['server_id']);

        if (($data['type'] ?? null) === WebhookType::Discord->value) {
            $embeds = data_get($data, 'embeds', []);

            foreach ($embeds as &$embed) {
                $embed['color'] = hexdec(str_replace('#', '', data_get($embed, 'color')));
                $embed = collect($embed)->filter(fn ($key) => is_array($key) ? array_filter($key, fn ($arr_key) => !empty($arr_key)) : !empty($key))->all();
            }

            $flags = collect($data['flags'] ?? [])->reduce(fn ($carry, $bit) => $carry | $bit, 0);
            $selected = data_get($data, 'allowed_mentions', []);
            $allowedMentions = $selected ? ['parse' => array_values($selected)] : [];

            $tmp = collect([
                'username' => data_get($data, 'username'),
                'avatar_url' => data_get($data, 'avatar_url'),
                'content' => data_get($data, 'content'),
                'image' => data_get($data, 'image'),
                'thumbnail' => data_get($data, 'thumbnail'),
                'embeds' => $embeds,
                'thread_name' => data_get($data, 'thread_name'),
                'flags' => $flags,
                'allowed_mentions' => $allowedMentions,
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
