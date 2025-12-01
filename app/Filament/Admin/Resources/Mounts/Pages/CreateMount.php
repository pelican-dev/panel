<?php

namespace App\Filament\Admin\Resources\Mounts\Pages;

use App\Filament\Admin\Resources\Mounts\MountResource;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\IconSize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateMount extends CreateRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = MountResource::class;

    protected static bool $canCreateAnother = false;

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            $this->getCreateFormAction()->formId('form')
                ->iconButton()->iconSize(IconSize::ExtraLarge)
                ->icon('tabler-file-plus'),
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
