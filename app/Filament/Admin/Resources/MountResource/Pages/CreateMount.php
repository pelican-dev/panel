<?php

namespace App\Filament\Admin\Resources\MountResource\Pages;

use App\Filament\Admin\Resources\MountResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateMount extends CreateRecord
{
    protected static string $resource = MountResource::class;

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
        $data['uuid'] ??= Str::uuid()->toString();
        $data['user_mountable'] = 1;

        return parent::handleRecordCreation($data);
    }
}
