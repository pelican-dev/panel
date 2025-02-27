<?php

namespace App\Filament\Admin\Resources\WebhookResource\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Admin\Resources\WebhookResource;

class ViewWebhookConfiguration extends ViewRecord
{
    protected static string $resource = WebhookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
