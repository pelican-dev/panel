<?php

namespace App\Filament\Admin\Resources\WebhookResource\Pages;

use Filament\Actions\CreateAction;
use App\Models\WebhookConfiguration;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\WebhookResource;

class ListWebhookConfigurations extends ListRecords
{
    protected static string $resource = WebhookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->hidden(fn () => WebhookConfiguration::count() <= 0),
        ];
    }
}
