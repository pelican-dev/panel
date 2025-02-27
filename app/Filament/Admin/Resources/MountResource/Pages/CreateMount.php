<?php

namespace App\Filament\Admin\Resources\MountResource\Pages;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Admin\Resources\MountResource;

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
