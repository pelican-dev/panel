<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebhookResource\Pages;
use App\Models\WebhookConfiguration;
use App\Services\Webhooks\DiscoverWebhookEventsService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WebhookResource extends Resource
{
    protected static ?string $model = WebhookConfiguration::class;

    protected static ?string $navigationIcon = 'tabler-webhook';

    protected static ?string $label = 'Webhooks';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('endpoint')->activeUrl()->required(),
                Forms\Components\TextInput::make('description')->nullable(),
                Forms\Components\CheckboxList::make('events')->lazy()->options(fn () => DiscoverWebhookEventsService::toFilamentCheckboxList())->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
