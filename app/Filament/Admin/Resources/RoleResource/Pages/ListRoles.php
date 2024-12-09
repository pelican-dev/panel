<?php

namespace App\Filament\Admin\Resources\RoleResource\Pages;

use App\Filament\Admin\Resources\RoleResource;
use App\Models\Role;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction as CreateActionTable;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                    ->hidden()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('permissions_count')
                    ->label('Permissions')
                    ->badge()
                    ->counts('permissions')
                    ->formatStateUsing(fn (Role $role, $state) => $role->isRootAdmin() ? 'All' : $state),
                TextColumn::make('users_count')
                    ->label('Users')
                    ->counts('users')
                    ->icon('tabler-users'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->checkIfRecordIsSelectableUsing(fn (Role $role) => !$role->isRootAdmin() && $role->users_count <= 0)
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize(fn () => auth()->user()->can('delete role')),
                ]),
            ])
            ->emptyStateIcon('tabler-users-group')
            ->emptyStateDescription('')
            ->emptyStateHeading('No Roles')
            ->emptyStateActions([
                CreateActionTable::make('create')
                    ->label('Create Role')
                    ->button(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Role'),
        ];
    }
}
