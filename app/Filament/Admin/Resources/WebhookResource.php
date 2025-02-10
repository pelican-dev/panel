<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\WebhookResource\Pages;
use App\Models\WebhookConfiguration;
use Filament\Resources\Resource;

class WebhookResource extends Resource
{
    protected static ?string $model = WebhookConfiguration::class;

    protected static ?string $navigationIcon = 'tabler-webhook';

    protected static ?string $recordTitleAttribute = 'description';

    public static function getNavigationLabel(): string
    {
        return trans('admin/webhook.nav_title');
    }

    public static function getModelLabel(): string
    {
        return trans('admin/webhook.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('admin/webhook.model_label_plural');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('admin/dashboard.advanced');
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
