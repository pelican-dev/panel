<?php

namespace App\Filament\Admin\Resources\Webhooks\Pages;

use App\Enums\TablerIcon;
use App\Enums\WebhookScope;
use App\Facades\WebhookTypes;
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

        if ($schema = WebhookTypes::get($data['type'] ?? null)) {
            $data = $schema->mutateFormDataBeforeSave($data);
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
