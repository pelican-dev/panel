<?php

namespace App\Filament\App\Resources\BackupResource\Pages;

use App\Filament\App\Resources\BackupResource;
use App\Http\Controllers\Api\Client\Servers\BackupController;
use App\Models\Backup;
use App\Services\Backups\DownloadLinkService;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Http\Request;

class ListBackups extends ListRecords
{
    protected static string $resource = BackupResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //TODO
            ]);
    }

    public function table(Table $table): Table
    {
        $server = Filament::getTenant();

        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('bytes')
                    ->label('Size')
                    ->formatStateUsing(fn ($state) => $this->convertToReadableSize($state)),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('is_successful')
                    ->label('Successful')
                    ->boolean(),
                IconColumn::make('is_locked')
                    ->label('Lock Status')
                    ->icon(fn (Backup $backup) => !$backup->is_locked ? 'tabler-lock-open' : 'tabler-lock'),
            ])
            ->actions([
                Action::make('lock')
                    ->label(fn (Backup $backup) => !$backup->is_locked ? 'Lock' : 'Unlock')
                    ->action(fn (BackupController $backupController, Backup $backup, Request $request) => $backupController->toggleLock($request, $server, $backup)),
                Action::make('download')
                    ->url(function (DownloadLinkService $downloadLinkService, Backup $backup, Request $request) {
                        return $downloadLinkService->handle($backup, $request->user());
                    }, true),
                Action::make('restore'),
                Action::make('delete')
                    ->action(fn (BackupController $backupController, Backup $backup, Request $request) => $backupController->delete($request, $server, $backup)),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function convertToReadableSize($size)
    {
        $base = log($size) / log(1024);
        $suffix = ['', 'KB', 'MB', 'GB', 'TB'];
        $f_base = floor($base);

        return round(pow(1024, $base - floor($base)), 2) . $suffix[$f_base];
    }
}
