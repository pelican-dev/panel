<?php

namespace App\Filament\Admin\Resources\WebhookResource\Pages;

use App\Filament\Admin\Resources\WebhookResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWebhookConfiguration extends EditRecord
{
    protected static string $resource = WebhookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Delete')
                ->modalHeading('Are you sure you want to delete this?')
                ->modalDescription('')
                ->modalSubmitActionLabel('Delete'),
            $this->getSaveFormAction()->formId('form'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
