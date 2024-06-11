<?php

namespace App\Filament\Resources\EggResource\Pages;

use App\Filament\Resources\EggResource;
use App\Models\Egg;
use App\Services\Eggs\Sharing\EggImporterService;
use Exception;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
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
            ->searchable(true)
            ->defaultPaginationPageOption(25)
            ->checkIfRecordIsSelectableUsing(fn (Egg $egg) => $egg->servers_count <= 0)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->icon('tabler-egg')
                    ->description(fn ($record): ?string => (strlen($record->description) > 120) ? substr($record->description, 0, 120).'...' : $record->description)
                    ->wrap()
                    ->searchable()
                    ->sortable(),
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

            Actions\Action::make('import')
                ->label('Import')
                ->form([
                    Tabs::make('Tabs')
                        ->tabs([
                            Tabs\Tab::make('From File')
                                ->icon('tabler-file-upload')
                                ->schema([
                                    Forms\Components\FileUpload::make('egg')
                                        ->label('Egg')
                                        ->hint('This should be the json file ( egg-minecraft.json )')
                                        ->acceptedFileTypes(['application/json'])
                                        ->storeFiles(false)
                                        ->multiple(),
                                ]),
                            Tabs\Tab::make('From URL')
                                ->icon('tabler-world-upload')
                                ->schema([
                                    Forms\Components\TextInput::make('url')
                                        ->label('URL')
                                        ->hint('This URL should point to a single json file')
                                        ->url(),
                                ]),
                        ])
                        ->contained(false),

                ])
                ->action(function (array $data): void {
                    /** @var EggImporterService $eggImportService */
                    $eggImportService = resolve(EggImporterService::class);

                    if (!empty($data['egg'])) {
                        /** @var TemporaryUploadedFile[] $eggFile */
                        $eggFile = $data['egg'];

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
                    }

                    if (!empty($data['url'])) {
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
                    }

                    Notification::make()
                        ->title('Import Success')
                        ->success()
                        ->send();
                }),
        ];
    }
}
