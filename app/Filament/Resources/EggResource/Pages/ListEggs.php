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
                    ->description(fn ($record): ?string => $record->description)
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('author')
                    ->hidden()
                    ->searchable(),
                Tables\Columns\TextColumn::make('servers_count')
                    ->counts('servers')
                    ->icon('tabler-server')
                    ->label('Servers'),
                Tables\Columns\TextColumn::make('script_container')
                    ->searchable()
                    ->hidden(),
                Tables\Columns\TextColumn::make('copyFrom.name')
                    ->hidden()
                    ->sortable(),
                Tables\Columns\TextColumn::make('script_entry')
                    ->hidden()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ExportAction::make()
                    ->icon('tabler-download')
                    ->label('Export')
                    // uses old admin panel export service
                    ->url(fn (Egg $egg): string => route('admin.eggs.export', ['egg' => $egg])),
            ])
            ->headerActions([
                //
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
