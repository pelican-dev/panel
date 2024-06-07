<?php

namespace App\Filament\Resources\EggResource\Pages;

use App\Filament\Resources\EggResource;
use App\Models\Egg;
use App\Services\Eggs\Sharing\EggImporterService;
use Exception;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Tables;

class ListEggs extends ListRecords
{
    protected static string $resource = EggResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->defaultPaginationPageOption(25)
            ->checkIfRecordIsSelectableUsing(fn (Egg $egg) => $egg->servers_count <= 0)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Id')
                    ->hidden()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->icon('tabler-egg')
                    ->description(fn ($record): ?string => (strlen($record->description) > 120) ? substr($record->description, 0, 120).'...' : $record->description)
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('servers_count')
                    ->counts('servers')
                    ->icon('tabler-server')
                    ->label('Servers'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ExportAction::make()
                    ->icon('tabler-download')
                    ->label('Export')
                    ->color('primary')
                    // TODO uses old admin panel export service
                    ->url(fn (Egg $egg): string => route('admin.eggs.export', ['egg' => $egg])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('create')->label('Create Egg'),

            Actions\Action::make('import_file')
                ->label('Import File')
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
                            $eggImportService->fromFile($file);
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

            Actions\Action::make('import_url')
                ->label('Import from URL')
                ->form([
                    Forms\Components\TextInput::make('url')
                        ->url(),
                ])
                ->action(function (array $data): void {
                    /** @var EggImporterService $eggImportService */
                    $eggImportService = resolve(EggImporterService::class);

                    try {
                        $eggImportService->fromUrl($data['url']);
                    } catch (Exception $exception) {
                        Notification::make()
                            ->title('Import Failed')
                            ->danger()
                            ->send();

                        report($exception);

                        return;
                    }

                    Notification::make()
                        ->title('Import Success')
                        ->success()
                        ->send();
                }),
        ];
    }
}
