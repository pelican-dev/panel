<?php

namespace App\Filament\Components\Actions;

use App\Models\Permission;
use App\Models\Server;
use App\Services\Schedules\Sharing\ScheduleImporterService;
use Exception;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Arr;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImportScheduleAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'import';
    }

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Server $server */
        $server = Filament::getTenant();

        $this->label(trans('filament-actions::import.modal.actions.import.label'));

        $this->authorize(fn () => auth()->user()->can(Permission::ACTION_SCHEDULE_CREATE, $server));

        $this->form([
            Tabs::make('Tabs')
                ->contained(false)
                ->tabs([
                    Tab::make(trans('admin/schedule.import.file'))
                        ->icon('tabler-file-upload')
                        ->schema([
                            FileUpload::make('files')
                                ->label(trans('admin/schedule.model_label'))
                                ->hint(trans('admin/schedule.import.schedule_help'))
                                ->acceptedFileTypes(['application/json'])
                                ->preserveFilenames()
                                ->previewable(false)
                                ->storeFiles(false)
                                ->multiple(true),
                        ]),
                    Tab::make(trans('admin/schedule.import.url'))
                        ->icon('tabler-world-upload')
                        ->schema([
                            Repeater::make('urls')
                                ->label('')
                                ->itemLabel(fn (array $state) => str($state['url'])->afterLast('/schedule-')->before('.json')->headline())
                                ->hint(trans('admin/schedule.import.url_help'))
                                ->addActionLabel(trans('admin/schedule.import.add_url'))
                                ->grid(2)
                                ->reorderable(false)
                                ->addable(true)
                                ->deletable(fn (array $state) => count($state) > 1)
                                ->schema([
                                    TextInput::make('url')
                                        ->live()
                                        ->label(trans('admin/schedule.import.url'))
                                        ->url()
                                        ->endsWith('.json')
                                        ->validationAttribute(trans('admin/schedule.import.url')),
                                ]),
                        ]),
                ]),
        ]);

        $this->action(function (array $data, ScheduleImporterService $service) use ($server) {
            $schedules = array_merge(collect($data['urls'])->flatten()->whereNotNull()->unique()->all(), Arr::wrap($data['files']));
            if (empty($schedules)) {
                return;
            }

            [$success, $failed] = [collect(), collect()];

            foreach ($schedules as $schedule) {
                if ($schedule instanceof TemporaryUploadedFile) {
                    $name = str($schedule->getClientOriginalName())->afterLast('schedule-')->before('.json')->headline();
                    $method = 'fromFile';
                } else {
                    $schedule = str($schedule);
                    $schedule = $schedule->contains('github.com') ? $schedule->replaceFirst('blob', 'raw') : $schedule;
                    $name = $schedule->afterLast('/schedule-')->before('.json')->headline();
                    $method = 'fromUrl';
                }
                try {
                    $service->$method($schedule, $server);
                    $success->push($name);
                } catch (Exception $exception) {
                    $failed->push($name);
                    report($exception);
                }
            }

            if ($failed->count() > 0) {
                Notification::make()
                    ->title(trans('admin/schedule.import.import_failed'))
                    ->body($failed->join(', '))
                    ->danger()
                    ->send();
            }
            if ($success->count() > 0) {
                Notification::make()
                    ->title(trans('admin/schedule.import.import_success'))
                    ->body($success->join(', '))
                    ->success()
                    ->send();
            }
        });
    }
}
