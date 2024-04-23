<?php

namespace App\Filament\Resources\MountResource\Pages;

use App\Filament\Resources\MountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Filament\Tables;

class ListMounts extends ListRecords
{
    protected static string $resource = MountResource::class;
    public function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('source')
                    ->searchable(),
                Tables\Columns\TextColumn::make('target')
                    ->searchable(),
                Tables\Columns\IconColumn::make('read_only')
                    ->icon(fn (bool $state) => $state ? 'tabler-circle-check-filled' : 'tabler-circle-x-filled')
                    ->color(fn (bool $state) => $state ? 'success' : 'danger')
                    ->sortable(),
                Tables\Columns\IconColumn::make('user_mountable')
                    ->hidden()
                    ->icon(fn (bool $state) => $state ? 'tabler-circle-check-filled' : 'tabler-circle-x-filled')
                    ->color(fn (bool $state) => $state ? 'success' : 'danger')
                    ->sortable(),
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
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
