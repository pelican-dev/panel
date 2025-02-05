<?php

namespace App\Filament\Admin\Resources\ApiKeyResource\Pages;

use App\Filament\Admin\Resources\ApiKeyResource;
use App\Models\ApiKey;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateApiKey extends CreateRecord
{
    protected static string $resource = ApiKeyResource::class;

    protected static bool $canCreateAnother = false;

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
        $data['identifier'] = ApiKey::generateTokenIdentifier(ApiKey::TYPE_APPLICATION);
        $data['token'] = str_random(ApiKey::KEY_LENGTH);
        $data['user_id'] = auth()->user()->id;
        $data['key_type'] = ApiKey::TYPE_APPLICATION;

        $permissions = [];

        foreach (ApiKey::getPermissionList() as $permission) {
            if (isset($data['permissions_' . $permission])) {
                $permissions[$permission] = intval($data['permissions_' . $permission]);
                unset($data['permissions_' . $permission]);
            }
        }

        $data['permissions'] = $permissions;

        return parent::handleRecordCreation($data);
    }
}
