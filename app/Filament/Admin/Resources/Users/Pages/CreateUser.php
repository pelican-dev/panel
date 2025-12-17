<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Models\Role;
use App\Services\Users\UserCreationService;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\IconSize;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = UserResource::class;

    protected static bool $canCreateAnother = false;

    private UserCreationService $service;

    public function boot(UserCreationService $service): void
    {
        $this->service = $service;
    }

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            $this->getCreateFormAction()->formId('form')
                ->iconButton()->iconSize(IconSize::ExtraLarge)
                ->icon('tabler-user-plus'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function prepareForValidation($attributes): array
    {
        $attributes['data']['email'] = mb_strtolower($attributes['data']['email']);

        return $attributes;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $data['root_admin'] = false;

        $roles = $data['roles'];
        $roles = collect($roles)->map(fn ($role) => Role::findById($role));
        unset($data['roles']);

        $user = $this->service->handle($data);

        $user->syncRoles($roles);

        return $user;
    }
}
