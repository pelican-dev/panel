<?php

namespace App\Filament\Admin\Resources\DatabaseHostResource\Pages;

use App\Filament\Admin\Resources\DatabaseHostResource;
use App\Models\DatabaseHost;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ListDatabaseHosts extends ListRecords
{
    protected static string $resource = DatabaseHostResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->columns([
                TextColumn::make('name')
                    ->label(trans('admin/databasehost.table.name'))
                    ->searchable(),
                TextColumn::make('host')
                    ->label(trans('admin/databasehost.table.host'))
                    ->searchable(),
                TextColumn::make('port')
                    ->label(trans('admin/databasehost.table.port'))
                    ->sortable(),
                TextColumn::make('username')
                    ->label(trans('admin/databasehost.table.username'))
                    ->searchable(),
                TextColumn::make('databases_count')
                    ->counts('databases')
                    ->icon('tabler-database')
                    ->label(trans('admin/databasehost.databases')),
                TextColumn::make('nodes.name')
                    ->icon('tabler-server-2')
                    ->badge()
                    ->placeholder(trans('admin/databasehost.no_nodes'))
                    ->sortable(),
            ])
            ->checkIfRecordIsSelectableUsing(fn (DatabaseHost $databaseHost) => !$databaseHost->databases_count)
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize(fn () => auth()->user()->can('delete databasehost')),
                ]),
            ])
            ->emptyStateIcon('tabler-database')
            ->emptyStateDescription('')
            ->emptyStateHeading(trans('admin/databasehost.no_database_hosts'))
            ->emptyStateActions([
                CreateAction::make('create')
                    ->label(trans('admin/databasehost.create_action', ['action' => trans('filament-actions::create.single.modal.actions.create.label')]))
                    ->button(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('create')
                ->label(trans('admin/databasehost.create_action', ['action' => trans('filament-actions::create.single.modal.actions.create.label')]))
                ->hidden(fn () => DatabaseHost::count() <= 0),
        ];
    }
}
