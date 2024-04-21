<?php

namespace App\Filament\Resources\WebhookConfigurationResource\Pages;

use App\Filament\Resources\WebhookConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebhookConfigurations extends ListRecords
{
    protected static string $resource = WebhookConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
