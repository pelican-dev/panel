<?php

namespace App\Filament\Admin\Resources\WebhookResource\Pages;

use App\Filament\Admin\Resources\WebhookResource;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\CreateRecord;

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
            $this->getCreateFormAction()->formId('form'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
