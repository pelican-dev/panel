<?php

namespace App\Filament\Admin\Resources\DatabaseResource\Pages;

use App\Filament\Admin\Resources\DatabaseResource;
use App\Tables\Columns\DateTimeColumn;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ListDatabases extends ListRecords
{
    protected static string $resource = DatabaseResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('server.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('database_host_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('database')
                    ->searchable(),
                TextColumn::make('username')
                    ->searchable(),
                TextColumn::make('remote')
                    ->searchable(),
                TextColumn::make('max_connections')
                    ->numeric()
                    ->sortable(),
                DateTimeColumn::make('created_at')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                DateTimeColumn::make('updated_at')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize(fn () => auth()->user()->can('delete database')),
                ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
