<?php

namespace App\Filament\Resources\EggResource\Pages;

use App\Filament\Resources\EggResource;
use App\Services\Eggs\Sharing\EggImporterService;
use Exception;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ListEggs extends ListRecords
{
    protected static string $resource = EggResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Actions\Action::make('import')
                ->label('Import')
                ->form([
                    Forms\Components\FileUpload::make('egg')
                        ->acceptedFileTypes(['application/json'])
                        ->storeFiles(false)
                        ->multiple(),
                ])
                ->action(function (array $data): void {
                    /** @var TemporaryUploadedFile $eggFile */
                    $eggFile = $data['egg'];

                    /** @var EggImporterService $eggImportService */
                    $eggImportService = resolve(EggImporterService::class);

                    foreach ($eggFile as $file) {
                        try {
                            $eggImportService->handle($file);
                        } catch (Exception $exception) {
                            Notification::make()
                                ->title('Import Failed')
                                ->danger()
                                ->send();

                            report($exception);

                            return;
                        }
                    }

                    Notification::make()
                        ->title('Import Success')
                        ->success()
                        ->send();
                }),
        ];
    }
}
