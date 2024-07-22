<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('guard_name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('permissions_count')
                    ->label('Permissions')
                    ->badge()
                    ->counts('permissions')
                    ->formatStateUsing(fn (Role $role, $state) => $role->name === 'Root Admin' ? 'All' : $state),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->checkIfRecordIsSelectableUsing(fn (Role $role) => $role->name !== 'Root Admin')
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('tabler-users-group')
            ->emptyStateDescription('')
            ->emptyStateHeading('No Roles')
            ->emptyStateActions([
                CreateAction::make('create')
                    ->label('Create Role')
                    ->button(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Role'),
        ];
    }
}
