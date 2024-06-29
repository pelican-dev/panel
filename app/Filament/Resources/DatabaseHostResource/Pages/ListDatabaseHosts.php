<?php

namespace App\Filament\Resources\DatabaseHostResource\Pages;

use App\Filament\Resources\DatabaseHostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ListDatabaseHosts extends ListRecords
{
    protected static string $resource = DatabaseHostResource::class;

    protected ?string $heading = 'Database Hosts';

    public function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('host')
                    ->searchable(),
                TextColumn::make('port')
                    ->sortable(),
                TextColumn::make('username')
                    ->searchable(),
                TextColumn::make('max_databases')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('node.name')
                    ->numeric()
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('create')->label('New Database Host'),
        ];
    }
}
