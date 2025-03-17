<?php

namespace App\Filament\Server\Resources\ScheduleResource\Pages;

use App\Facades\Activity;
use App\Filament\Server\Resources\ScheduleResource;
use App\Models\Schedule;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchedule extends EditRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function afterSave(): void
    {
        /** @var Schedule $schedule */
        $schedule = $this->record;

        Activity::event('server:schedule.update')
            ->property('name', $schedule->name)
            ->log();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(function ($record) {
                    Activity::event('server:schedule.delete')
                        ->property('name', $record->name)
                        ->log();
                }),
            $this->getSaveFormAction()->formId('form')->label('Save'),
            $this->getCancelFormAction()->formId('form'),
        ];
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
