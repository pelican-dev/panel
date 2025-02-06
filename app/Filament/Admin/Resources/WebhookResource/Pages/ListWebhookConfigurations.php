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
                    ->label(trans('admin/webhook.table.description')),
                TextColumn::make('endpoint')
                    ->label(trans('admin/webhook.table.endpoint')),
            ])
            ->actions([
                DeleteAction::make(),
                EditAction::make(),
            ])
            ->emptyStateIcon('tabler-webhook')
            ->emptyStateDescription('')
            ->emptyStateHeading(trans('admin/webhook.no_webhooks'))
            ->emptyStateActions([
                CreateAction::make('create')
                    ->label(trans('admin/webhook.create_action', ['action' => trans('filament-actions::create.single.modal.actions.create.label')]))
                    ->button(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(trans('admin/webhook.create_action', ['action' => trans('filament-actions::create.single.modal.actions.create.label')]))
                ->hidden(fn () => WebhookConfiguration::count() <= 0),
        ];
    }
}
