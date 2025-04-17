<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\Role;
use App\Services\Users\UserCreationService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static bool $canCreateAnother = false;

    private UserCreationService $service;

    public function boot(UserCreationService $service): void
    {
        $this->service = $service;
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->getCreateFormAction()->formId('form'),
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
