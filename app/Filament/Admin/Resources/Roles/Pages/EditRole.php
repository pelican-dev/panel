<?php

namespace App\Filament\Admin\Resources\Roles\Pages;

use App\Filament\Admin\Resources\Roles\RoleResource;
use App\Models\Role;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\IconSize;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;

/**
 * @property Role $record
 */
class EditRole extends EditRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = RoleResource::class;

    public Collection $permissions;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->permissions = collect($data)
            ->filter(function ($permission, $key) {
                return !in_array($key, ['name', 'guard_name']);
            })
            ->values()
            ->flatten()
            ->unique();

        return Arr::only($data, ['name', 'guard_name']);
    }

    protected function afterSave(): void
    {
        $permissionModels = collect();
        $this->permissions->each(function ($permission) use ($permissionModels) {
            $permissionModels->push(Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $this->data['guard_name'],
            ]));
        });

        $this->record->syncPermissions($permissionModels);
    }

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label(fn (Role $role) => $role->isRootAdmin() ? trans('admin/role.root_admin_delete') : ($role->users_count >= 1 ? trans('admin/role.in_use') : trans('filament-actions::delete.single.label')))
                ->disabled(fn (Role $role) => $role->isRootAdmin() || $role->users_count >= 1)
                ->iconButton()->iconSize(IconSize::ExtraLarge),
            $this->getSaveFormAction()->formId('form')
                ->iconButton()->iconSize(IconSize::ExtraLarge)
                ->icon('tabler-device-floppy'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
