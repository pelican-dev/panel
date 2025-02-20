<?php

namespace App\Filament\Components\Tables\Actions;

use App\Services\Eggs\Sharing\EggImporterService;
use Exception;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

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
            Tabs::make()
                ->contained(false)
                ->tabs([
                    Tab::make(trans('admin/egg.import.file'))
                        ->icon('tabler-file-upload')
                        ->schema([
                            FileUpload::make('egg')
                                ->label(trans('admin/egg.model_label'))
                                ->hint(trans('admin/egg.import.egg_help'))
                                ->acceptedFileTypes(['application/json'])
                                ->storeFiles(false)
                                ->multiple(),
                        ]),
                    Tab::make(trans('admin/egg.import.url'))
                        ->icon('tabler-world-upload')
                        ->schema([
                            TextInput::make('url')
                                ->label(trans('admin/egg.import.url'))
                                ->hint(trans('admin/egg.import.url_help'))
                                ->url(),
                        ]),
                ]),
        ]);

        $this->action(function (array $data, EggImporterService $eggImportService): void {
            try {
                if (!empty($data['egg'])) {
                    $eggFile = $data['egg'];

                    foreach ($eggFile as $file) {
                        $eggImportService->fromFile($file);
                    }
                }

                if (!empty($data['url'])) {
                    $eggImportService->fromUrl($data['url']);
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
