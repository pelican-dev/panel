<?php

namespace App\Filament\Resources\WebhookConfigurationResource\Pages;

use App\Filament\Resources\WebhookResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWebhookConfiguration extends CreateRecord
{
    protected static string $resource = WebhookResource::class;
}
