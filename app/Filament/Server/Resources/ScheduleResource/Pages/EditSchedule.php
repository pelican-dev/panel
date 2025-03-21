<?php

namespace App\Filament\Server\Resources\ScheduleResource\Pages;

use App\Facades\Activity;
use App\Filament\Server\Resources\ScheduleResource;
use App\Helpers\Utilities;
use App\Models\Schedule;
use Exception;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;

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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        try {
            $data['next_run_at'] = Utilities::getScheduleNextRunDate(
                $data['cron_minute'],
                $data['cron_hour'],
                $data['cron_day_of_month'],
                $data['cron_month'],
                $data['cron_day_of_week']
            );
        } catch (Exception) {
            Notification::make()
                ->title('The cron data provided does not evaluate to a valid expression')
                ->danger()
                ->send();

            throw new Halt();
        }

        return $data;
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
