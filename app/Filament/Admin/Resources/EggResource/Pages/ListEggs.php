<?php

namespace App\Filament\Admin\Resources\EggResource\Pages;

use App\Filament\Admin\Resources\EggResource;
use App\Filament\Components\Actions\ImportEggAction as ImportEggHeaderAction;
use App\Filament\Components\Tables\Actions\ExportEggAction;
use App\Filament\Components\Tables\Actions\ImportEggAction;
use App\Filament\Components\Tables\Actions\UpdateEggAction;
use App\Models\Egg;
use Filament\Actions\CreateAction as CreateHeaderAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ListEggs extends ListRecords
{
    protected static string $resource = EggResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->searchable(true)
            ->defaultPaginationPageOption(25)
            ->checkIfRecordIsSelectableUsing(fn (Egg $egg) => $egg->servers_count <= 0)
            ->columns([
                TextColumn::make('id')
                    ->label('Id')
                    ->hidden(),
                TextColumn::make('name')
                    ->icon('tabler-egg')
                    ->description(fn ($record): ?string => (strlen($record->description) > 120) ? substr($record->description, 0, 120).'...' : $record->description)
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('servers_count')
                    ->counts('servers')
                    ->icon('tabler-server')
                    ->label('Servers'),
            ])
            ->actions([
                EditAction::make(),
                ExportEggAction::make(),
                UpdateEggAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize(fn () => auth()->user()->can('delete egg')),
                ]),
            ])
            ->emptyStateIcon('tabler-eggs')
            ->emptyStateDescription('')
            ->emptyStateHeading('No Eggs')
            ->emptyStateActions([
                CreateAction::make()
                    ->label('Create Egg'),
                ImportEggAction::make(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            ImportEggHeaderAction::make(),
            CreateHeaderAction::make()
                ->label('Create Egg'),
        ];
    }
}
