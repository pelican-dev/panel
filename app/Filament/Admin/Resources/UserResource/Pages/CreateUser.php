<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Services\Users\UserCreationService;
use App\Filament\Admin\Resources\UserResource;

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
