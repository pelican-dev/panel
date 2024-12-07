<?php

namespace App\Filament\Admin\Resources;

use App\Models\WebhookConfiguration;
use Filament\Resources\Resource;

class WebhookResource extends Resource
{
    protected static ?string $model = WebhookConfiguration::class;

    protected static ?string $navigationIcon = 'tabler-webhook';

    protected static ?string $navigationGroup = 'Advanced';

    protected static ?string $label = 'Webhooks';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Admin\Resources\WebhookResource\Pages\ListWebhookConfigurations::route('/'),
            'create' => \App\Filament\Admin\Resources\WebhookResource\Pages\CreateWebhookConfiguration::route('/create'),
            'edit' => \App\Filament\Admin\Resources\WebhookResource\Pages\EditWebhookConfiguration::route('/{record}/edit'),
        ];
    }
}
