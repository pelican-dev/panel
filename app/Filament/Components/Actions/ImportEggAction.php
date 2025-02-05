<?php

namespace App\Filament\Components\Actions;

use App\Models\Egg;
use App\Services\Eggs\Sharing\EggImporterService;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
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
                    Tab::make('From File')
                        ->icon('tabler-file-upload')
                        ->schema([
                            FileUpload::make('egg')
                                ->label('Egg')
                                ->hint('This should be the json file ( egg-minecraft.json )')
                                ->acceptedFileTypes(['application/json'])
                                ->storeFiles(false)
                                ->multiple(),
                        ]),
                    Tab::make('From URL')
                        ->icon('tabler-world-upload')
                        ->schema([
                            TextInput::make('url')
                                ->label('URL')
                                ->hint('This URL should point to a single json file')
                                ->default(fn (Egg $egg) => $egg->update_url)
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
                    ->title('Import Failed')
                    ->body($exception->getMessage())
                    ->danger()
                    ->send();

                report($exception);

                return;
            }

            Notification::make()
                ->title('Import Success')
                ->success()
                ->send();
        });
    }
}
