<?php

namespace App\Filament\Admin\Resources\MountResource\Pages;

use App\Filament\Admin\Resources\MountResource;
use App\Models\Mount;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ListMounts extends ListRecords
{
    protected static string $resource = MountResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('source')
                    ->searchable(),
                TextColumn::make('target')
                    ->searchable(),
                IconColumn::make('read_only')
                    ->icon(fn (bool $state) => $state ? 'tabler-circle-check-filled' : 'tabler-circle-x-filled')
                    ->color(fn (bool $state) => $state ? 'success' : 'danger')
                    ->sortable(),
                IconColumn::make('user_mountable')
                    ->hidden()
                    ->icon(fn (bool $state) => $state ? 'tabler-circle-check-filled' : 'tabler-circle-x-filled')
                    ->color(fn (bool $state) => $state ? 'success' : 'danger')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize(fn () => auth()->user()->can('delete mount')),
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
