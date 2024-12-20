<?php

namespace App\Filament\Admin\Resources\WebhookResource\Pages;

use App\Filament\Admin\Resources\WebhookResource;
use App\Models\WebhookConfiguration;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;

class ListWebhookConfigurations extends ListRecords
{
    protected static string $resource = WebhookResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')
                    ->label('Description'),
                TextColumn::make('endpoint')
                    ->label('Endpoint'),
            ])
            ->actions([
                DeleteAction::make()
                    ->label('Delete'),
                EditAction::make()
                    ->label('Edit'),
            ])
            ->emptyStateIcon('tabler-webhook')
            ->emptyStateDescription('')
            ->emptyStateHeading('No Webhooks')
            ->emptyStateActions([
                CreateAction::make('create')
                    ->label('Create Webhook')
                    ->button(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Webhook')
                ->hidden(fn () => WebhookConfiguration::count() <= 0),
        ];
    }
}
