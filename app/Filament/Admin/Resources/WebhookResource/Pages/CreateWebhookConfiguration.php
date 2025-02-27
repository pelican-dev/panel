<?php

namespace App\Filament\Admin\Resources\WebhookResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Admin\Resources\WebhookResource;

class CreateWebhookConfiguration extends CreateRecord
{
    protected static string $resource = WebhookResource::class;

    protected static bool $canCreateAnother = false;

    protected function getHeaderActions(): array
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
