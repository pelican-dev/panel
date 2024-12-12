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
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImportEggAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'import';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Import');

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
                                ->url(),
                        ]),
                ]),
        ]);

        $this->action(function (array $data, EggImporterService $eggImportService): void {
            try {
                if (!empty($data['egg'])) {
                    /** @var TemporaryUploadedFile[] $eggFile */
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
