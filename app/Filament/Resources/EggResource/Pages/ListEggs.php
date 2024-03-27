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
                ->label('Import Egg')
                ->form([
                    Forms\Components\FileUpload::make('egg')
                        ->acceptedFileTypes(['application/json'])
                        ->storeFiles(false),
                ])
                ->action(function (array $data): void {
                    /** @var TemporaryUploadedFile $eggFile */
                    $eggFile = $data['egg'];

                    /** @var EggImporterService $eggImportService */
                    $eggImportService = resolve(EggImporterService::class);

                    try {
                        $newEgg = $eggImportService->handle($eggFile);
                    } catch (Exception $exception) {
                        Notification::make()
                            ->title("Imported egg successfully: {$exception->getMessage()}")
                            ->success()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title("Imported egg successfully: $newEgg->name")
                        ->success()
                        ->send();

                    redirect()->route('filament.admin.resources.eggs.edit', [$newEgg]);

                    // $livewire->redirect(route('filament.admin.resources.eggs.edit', [$newEgg]));
                })
        ];
    }
}
