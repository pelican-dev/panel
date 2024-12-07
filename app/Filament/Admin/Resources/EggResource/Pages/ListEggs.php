<?php

namespace App\Filament\Admin\Resources\EggResource\Pages;

use App\Filament\Admin\Resources\EggResource;
use App\Models\Egg;
use App\Services\Eggs\Sharing\EggExporterService;
use App\Services\Eggs\Sharing\EggImporterService;
use Exception;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

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
                TextColumn::make('id')
                    ->label('Id')
                    ->hidden(),
                TextColumn::make('name')
                    ->icon('tabler-egg')
                    ->description(fn ($record): ?string => (strlen($record->description) > 120) ? substr($record->description, 0, 120).'...' : $record->description)
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('servers_count')
                    ->counts('servers')
                    ->icon('tabler-server')
                    ->label('Servers'),
            ])
            ->actions([
                EditAction::make(),
                Action::make('export')
                    ->icon('tabler-download')
                    ->label('Export')
                    ->color('primary')
                    ->action(fn (EggExporterService $service, Egg $egg) => response()->streamDownload(function () use ($service, $egg) {
                        echo $service->handle($egg->id);
                    }, 'egg-' . $egg->getKebabName() . '.json'))
                    ->authorize(fn () => auth()->user()->can('export egg')),
                Action::make('update')
                    ->icon('tabler-cloud-download')
                    ->label('Update')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Are you sure you want to update this egg?')
                    ->modalDescription('If you made any changes to the egg they will be overwritten!')
                    ->modalIconColor('danger')
                    ->modalSubmitAction(fn (Actions\StaticAction $action) => $action->color('danger'))
                    ->action(function (Egg $egg, EggImporterService $eggImporterService) {
                        try {
                            $eggImporterService->fromUrl($egg->update_url, $egg);

                            cache()->forget("eggs.{$egg->uuid}.update");
                        } catch (Exception $exception) {
                            Notification::make()
                                ->title('Egg Update failed')
                                ->body($exception->getMessage())
                                ->danger()
                                ->send();

                            report($exception);

                            return;
                        }

                        Notification::make()
                            ->title('Egg updated')
                            ->success()
                            ->send();
                    })
                    ->authorize(fn () => auth()->user()->can('import egg'))
                    ->visible(fn (Egg $egg) => cache()->get("eggs.{$egg->uuid}.update", false)),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize(fn () => auth()->user()->can('delete egg')),
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
                        ])
                        ->contained(false),

                ])
                ->action(function (array $data, EggImporterService $eggImportService): void {
                    if (!empty($data['egg'])) {
                        /** @var TemporaryUploadedFile[] $eggFile */
                        $eggFile = $data['egg'];

                        foreach ($eggFile as $file) {
                            try {
                                $eggImportService->fromFile($file);
                            } catch (Exception $exception) {
                                Notification::make()
                                    ->title('Import Failed')
                                    ->body($exception->getMessage())
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
                                ->body($exception->getMessage())
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
                })
                ->authorize(fn () => auth()->user()->can('import egg')),
        ];
    }
}
