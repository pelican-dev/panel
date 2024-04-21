<?php

namespace App\Filament\Resources\WebhookConfigurationResource\Pages;

use App\Filament\Resources\WebhookConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebhookConfiguration extends EditRecord
{
    protected static string $resource = WebhookConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
