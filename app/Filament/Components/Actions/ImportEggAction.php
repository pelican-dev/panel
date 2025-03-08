<?php

namespace App\Filament\Components\Actions;

use App\Models\Egg;
use App\Services\Eggs\Sharing\EggImporterService;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class ImportEggAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'import';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(trans('filament-actions::import.modal.actions.import.label'));

        $this->authorize(fn () => auth()->user()->can('import egg'));

        $this->form([
            Tabs::make('Tabs')
                ->contained(false)
                ->tabs([
                    Tab::make(trans('admin/egg.import.file'))
                        ->icon('tabler-file-upload')
                        ->schema([
                            FileUpload::make('egg')
                                ->label('Egg')
                                ->hint(trans('admin/egg.import.egg_help'))
                                ->acceptedFileTypes(['application/json'])
                                ->storeFiles(false)
                                ->multiple(),
                        ]),
                    Tab::make(trans('admin/egg.import.url'))
                        ->icon('tabler-world-upload')
                        ->schema([
                            Repeater::make('url')
                                ->label('Egg')
                                ->hint(trans('admin/egg.import.url_help'))
                                ->addActionLabel(trans('admin/egg.import.add_url'))
                                ->unique()
                                ->reorderable(false)
                                ->schema([
                                    TextInput::make('url')
                                        ->default(fn (Egg $egg) => $egg->update_url)
                                        ->label(trans('admin/egg.import.url'))
                                        ->placeholder('https://raw.githubusercontent.com/pelican-eggs/generic/main/nodejs/egg-node-js-generic.json')
                                        ->url(),
                                ]),
                        ]),
                ]),
        ]);

        $this->action(function (array $data, EggImporterService $eggImportService): void {
            try {
                if (!empty($data['egg'])) {
                    $eggFiles = $data['egg'];

                    foreach ($eggFiles as $file) {
                        $eggImportService->fromFile($file);
                    }
                }

                if (!empty($data['url'])) {
                    $eggUrls = collect($data['url'])->flatten()->all();

                    foreach ($eggUrls as $url) {
                        $eggImportService->fromUrl($url);
                    }
                }
            } catch (Exception $exception) {
                Notification::make()
                    ->title(trans('admin/egg.import.import_failed'))
                    ->body($exception->getMessage())
                    ->danger()
                    ->send();

                report($exception);

                return;
            }

            Notification::make()
                ->title(trans('admin/egg.import.import_success'))
                ->success()
                ->send();
        });
    }
}
