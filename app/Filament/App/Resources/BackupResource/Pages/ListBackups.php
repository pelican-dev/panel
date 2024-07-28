<?php

namespace App\Filament\App\Resources\BackupResource\Pages;

use App\Enums\ServerState;
use App\Facades\Activity;
use App\Filament\App\Resources\BackupResource;
use App\Http\Controllers\Api\Client\Servers\BackupController;
use App\Models\Backup;
use App\Models\Permission;
use App\Repositories\Daemon\DaemonBackupRepository;
use App\Services\Backups\DownloadLinkService;
use App\Services\Backups\InitiateBackupService;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ListBackups extends ListRecords
{
    protected static string $resource = BackupResource::class;
    protected static bool $canCreateAnother = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->columnSpanFull()
                    ->required(),
                TextArea::make('ignored')
                    ->columnSpanFull()
                    ->label('Ignored Files & Directories'),
                Toggle::make('is_locked')
                    ->label('Lock?')
                    ->helperText('Prevents this backup from being deleted until explicitly unlocked.'),
            ]);
    }

    public function table(Table $table): Table
    {
        /** @var \App\Models\Server $server */
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
                    ->hidden(!auth()->user()->can(Permission::ACTION_BACKUP_DELETE, Filament::getTenant()))
                    ->label(fn (Backup $backup) => !$backup->is_locked ? 'Lock' : 'Unlock')
                    ->action(fn (BackupController $backupController, Backup $backup, Request $request) => $backupController->toggleLock($request, $server, $backup)),
                Action::make('download')
                    ->hidden(!auth()->user()->can(Permission::ACTION_BACKUP_DOWNLOAD, Filament::getTenant()))
                    ->url(function (DownloadLinkService $downloadLinkService, Backup $backup, Request $request) {
                        return $downloadLinkService->handle($backup, $request->user());
                    }, true),
                Action::make('restore')
                    ->hidden(!auth()->user()->can(Permission::ACTION_BACKUP_RESTORE, Filament::getTenant()))
                    ->form([
                        Placeholder::make('')
                            ->helperText('Your server will be stopped. You will not be able to control the power state, access the file manager, or create additional backups until this process is completed.'),
                        Checkbox::make('truncate')
                            ->label('Delete all files before restoring backup?'),
                    ])
                    ->action(function (Backup $backup, $data, DaemonBackupRepository $daemonRepository, DownloadLinkService $downloadLinkService) {

                        /** @var \App\Models\Server $server */
                        $server = Filament::getTenant();

                        if (!is_null($server->status)) {
                            throw new BadRequestHttpException('This server is not currently in a state that allows for a backup to be restored.');
                        }

                        if (!$backup->is_successful && is_null($backup->completed_at)) {
                            throw new BadRequestHttpException('This backup cannot be restored at this time: not completed or failed.');
                        }

                        $log = Activity::event('server:backup.restore')
                            ->subject($backup)
                            ->property(['name' => $backup->name, 'truncate' => $data['truncate']]);

                        $log->transaction(function () use ($downloadLinkService, $daemonRepository, $backup, $server, $data) {
                            // If the backup is for an S3 file we need to generate a unique Download link for
                            // it that will allow daemon to actually access the file.
                            if ($backup->disk === Backup::ADAPTER_AWS_S3) {
                                $url = $downloadLinkService->handle($backup, auth()->user());
                            }

                            // Update the status right away for the server so that we know not to allow certain
                            // actions against it via the Panel API.
                            $server->update(['status' => ServerState::RestoringBackup]);

                            $daemonRepository->setServer($server)->restore($backup, $url ?? null, $data['truncate']);
                        });

                        return Notification::make()->title('Restoring Backup')->send();
                    }),
                Action::make('delete')
                    ->fillForm(fn (Backup $backup): array => [
                        'name' => $backup->name,
                    ])
                    ->form([
                        TextInput::make('name')
                            ->label('Name')
                            ->disabled(),
                    ])
                    ->disabled(fn (Backup $backup): bool => $backup->is_locked)
                    ->hidden(!auth()->user()->can(Permission::ACTION_BACKUP_DELETE, Filament::getTenant()))
                    ->requiresConfirmation()
                    ->action(fn (BackupController $backupController, Backup $backup, Request $request) => $backupController->delete($request, $server, $backup)),
            ]);
    }

    protected function getHeaderActions(): array
    {
        /** @var \App\Models\Server $server */
        $server = Filament::getTenant();

        return [
            Actions\CreateAction::make()
                ->label(fn () => $server->backups()->count() >= $server->backup_limit ? 'Backup Limit Reached' : 'Create Backup')
                ->disabled(fn () => $server->backups()->count() >= $server->backup_limit)
                ->createAnother(false)
                ->action(function (InitiateBackupService $initiateBackupService, $data) {

                    /** @var \App\Models\Server $server */
                    $server = Filament::getTenant();

                    $action = $initiateBackupService
                        ->setIgnoredFiles(explode(PHP_EOL, $data['ignored'] ?? ''));

                    if (auth()->user()->can(Permission::ACTION_BACKUP_DELETE, $server)) {
                        $action->setIsLocked((bool) $data['is_locked']);
                    }

                    $backup = $action->handle($server, $data['name']);

                    Activity::event('server:backup.start')
                        ->subject($backup)
                        ->property(['name' => $backup->name, 'locked' => (bool) $data['is_locked']])
                        ->log();

                    return Notification::make()
                        ->title('Backup Created')
                        ->body($backup->name . ' created.')
                        ->success()
                        ->send();
                }),
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
