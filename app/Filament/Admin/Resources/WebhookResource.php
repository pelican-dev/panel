<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\WebhookResource\Pages;
use App\Models\WebhookConfiguration;
use Filament\Resources\Resource;

class WebhookResource extends Resource
{
    protected static ?string $model = WebhookConfiguration::class;

    protected static ?string $modelLabel = 'Webhook';

    protected static ?string $pluralModelLabel = 'Webhooks';

    protected static ?string $navigationIcon = 'tabler-webhook';

    protected static ?string $navigationGroup = 'Advanced';

    protected static ?string $recordTitleAttribute = 'description';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebhookConfigurations::route('/'),
            'create' => Pages\CreateWebhookConfiguration::route('/create'),
            'edit' => Pages\EditWebhookConfiguration::route('/{record}/edit'),
        ];
    }
}
