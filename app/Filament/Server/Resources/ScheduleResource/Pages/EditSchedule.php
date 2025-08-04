<?php

namespace App\Filament\Server\Resources\ScheduleResource\Pages;

use App\Facades\Activity;
use App\Filament\Components\Actions\ExportScheduleAction;
use App\Filament\Server\Resources\ScheduleResource;
use App\Models\Schedule;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\IconSize;

class EditSchedule extends EditRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

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
        $data['next_run_at'] = ScheduleResource::getNextRun(
            $data['cron_minute'],
            $data['cron_hour'],
            $data['cron_day_of_month'],
            $data['cron_month'],
            $data['cron_day_of_week']
        );

        return $data;
    }

    /** @return array<Actions\Action|Actions\ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->hiddenLabel()->iconButton()->iconSize(IconSize::Large)
                ->icon('tabler-trash')
                ->tooltip(trans('server/schedule.delete'))
                ->after(function ($record) {
                    Activity::event('server:schedule.delete')
                        ->property('name', $record->name)
                        ->log();
                }),
            ExportScheduleAction::make()
                ->hiddenLabel()->iconButton()->iconSize(IconSize::Large)
                ->icon('tabler-download')
                ->tooltip(trans('server/schedule.export')),
            $this->getSaveFormAction()->formId('form')
                ->hiddenLabel()->iconButton()->iconSize(IconSize::Large)
                ->icon('tabler-device-floppy')
                ->tooltip(trans('server/schedule.save')),
            $this->getCancelFormAction()->formId('form')
                ->hiddenLabel()->iconButton()->iconSize(IconSize::Large)
                ->icon('tabler-cancel')
                ->tooltip(trans('server/schedule.cancel')),
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
