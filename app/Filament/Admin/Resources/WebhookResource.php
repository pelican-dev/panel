<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\WebhookResource\Pages;
use App\Models\WebhookConfiguration;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Form;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

class WebhookResource extends Resource
{
    protected static ?string $model = WebhookConfiguration::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-webhook';

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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')
                    ->label(trans('admin/webhook.table.description')),
                TextColumn::make('endpoint')
                    ->label(trans('admin/webhook.table.endpoint')),
            ])
            ->actions([
                ViewAction::make()
                    ->hidden(fn ($record) => static::canEdit($record)),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make(),
            ])
            ->emptyStateIcon('tabler-webhook')
            ->emptyStateDescription('')
            ->emptyStateHeading(trans('admin/webhook.no_webhooks'))
            ->emptyStateActions([
                CreateAction::make(),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('endpoint')
                    ->label(trans('admin/webhook.endpoint'))
                    ->activeUrl()
                    ->required(),
                TextInput::make('description')
                    ->label(trans('admin/webhook.description'))
                    ->required(),
                CheckboxList::make('events')
                    ->lazy()
                    ->options(fn () => WebhookConfiguration::filamentCheckboxList())
                    ->searchable()
                    ->bulkToggleable()
                    ->columns(3)
                    ->columnSpanFull()
                    ->gridDirection('row')
                    ->required(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebhookConfigurations::route('/'),
            'create' => Pages\CreateWebhookConfiguration::route('/create'),
            'view' => Pages\ViewWebhookConfiguration::route('/{record}'),
            'edit' => Pages\EditWebhookConfiguration::route('/{record}/edit'),
        ];
    }
}
