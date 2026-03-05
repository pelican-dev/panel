<?php

namespace App\Filament\Server\Resources\Webhooks\Pages;

use App\Enums\WebhookScope;
use App\Filament\Server\Resources\Webhooks\WebhookResource;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateWebhook extends CreateRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = WebhookResource::class;

    protected static bool $canCreateAnother = false;

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            $this->getCancelFormAction()->formId('form')->icon('tabler-cancel'),
            $this->getCreateFormAction()->formId('form')->icon('tabler-plus'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        /** @var \App\Models\Server $server */
        $server = Filament::getTenant();
        $data['server_id'] = $server->id;
        $data['scope'] = WebhookScope::SERVER;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return EditWebhook::getUrl(['record' => $this->getRecord()]);
    }

    public function mount(): void
    {
        parent::mount();
        WebhookResource::sendHelpBanner();
    }
}
