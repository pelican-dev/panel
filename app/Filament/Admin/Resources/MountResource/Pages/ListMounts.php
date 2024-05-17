<?php

namespace App\Filament\Resources\MountResource\Pages;

use App\Filament\Resources\MountResource;
use App\Models\Mount;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\CreateAction;
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
            ])
            ->emptyStateIcon('tabler-layers-linked')
            ->emptyStateDescription('')
            ->emptyStateHeading('No Mounts')
            ->emptyStateActions([
                CreateAction::make('create')
                    ->label('Create Mount')
                    ->button(),
            ]);
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Mount')
                ->hidden(fn () => Mount::count() <= 0),
        ];
    }
}
