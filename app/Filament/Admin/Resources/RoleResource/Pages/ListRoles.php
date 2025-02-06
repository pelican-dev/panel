<?php

namespace App\Filament\Admin\Resources\RoleResource\Pages;

use App\Filament\Admin\Resources\RoleResource;
use App\Models\Role;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\BulkActionGroup;
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
                    ->label(trans('admin/role.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('guard_name')
                    ->hidden()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('permissions_count')
                    ->label(trans('admin/role.permissions'))
                    ->badge()
                    ->counts('permissions')
                    ->formatStateUsing(fn (Role $role, $state) => $role->isRootAdmin() ? trans('admin/role.all') : $state),
                TextColumn::make('users_count')
                    ->label(trans('admin/role.users'))
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
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(trans('admin/role.create_action', ['action' => trans('filament-actions::create.single.modal.actions.create.label')])),
        ];
    }
}
