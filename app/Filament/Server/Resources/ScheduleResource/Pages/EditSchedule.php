<?php

namespace App\Filament\Server\Resources\ScheduleResource\Pages;

use App\Facades\Activity;
use App\Filament\Server\Resources\ScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchedule extends EditRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            $this->getSaveFormAction()->formId('form')->label('Save'),
            $this->getCancelFormAction()->formId('form'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        Activity::event('server:schedule.update')
            ->property('name', $data['name'])
            ->log();

        return $data;
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
