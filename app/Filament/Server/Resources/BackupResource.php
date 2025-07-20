<?php

namespace App\Filament\Server\Resources;

use App\Filament\Server\Resources\BackupResource\Pages\ListBackups;
use App\Enums\BackupStatus;
use App\Enums\ServerState;
use App\Facades\Activity;
use App\Http\Controllers\Api\Client\Servers\BackupController;
use App\Models\Backup;
use App\Models\Permission;
use App\Models\Server;
use App\Repositories\Daemon\DaemonBackupRepository;
use App\Services\Backups\DownloadLinkService;
use App\Filament\Components\Tables\Columns\BytesColumn;
use App\Filament\Components\Tables\Columns\DateTimeColumn;
use App\Services\Backups\DeleteBackupService;
use App\Services\Backups\InitiateBackupService;
use App\Traits\Filament\BlockAccessInConflict;
use App\Traits\Filament\CanCustomizePages;
use App\Traits\Filament\CanCustomizeRelations;
use App\Traits\Filament\CanModifyForm;
use App\Traits\Filament\CanModifyTable;
use App\Traits\Filament\HasLimitBadge;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class BackupResource extends Resource
{
    use BlockAccessInConflict;
    use CanCustomizePages;
    use CanCustomizeRelations;
    use CanModifyForm;
    use CanModifyTable;
    use HasLimitBadge;

    protected static ?string $model = Backup::class;

    protected static ?int $navigationSort = 3;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-file-zip';

    protected static bool $canCreateAnother = false;

    protected static function getBadgeCount(): int
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $server->backups->count();
    }

    protected static function getBadgeLimit(): int
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $server->backup_limit;
    }

    public static function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->columnSpanFull(),
                TextArea::make('ignored')
                    ->columnSpanFull()
                    ->label('Ignored Files & Directories'),
                Toggle::make('is_locked')
                    ->label('Lock?')
                    ->helperText('Prevents this backup from being deleted until explicitly unlocked.'),
            ]);
    }

    /**
     * @throws Throwable
     * @throws ConnectionException
     */
    public static function defaultTable(Table $table): Table
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                BytesColumn::make('bytes')
                    ->label('Size'),
                DateTimeColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                IconColumn::make('is_locked')
                    ->visibleFrom('md')
                    ->label('Lock Status')
                    ->trueIcon('tabler-lock')
                    ->falseIcon('tabler-lock-open'),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('lock')
                        ->iconSize(IconSize::Large)
                        ->icon(fn (Backup $backup) => !$backup->is_locked ? 'tabler-lock' : 'tabler-lock-open')
                        ->authorize(fn () => auth()->user()->can(Permission::ACTION_BACKUP_DELETE, $server))
                        ->label(fn (Backup $backup) => !$backup->is_locked ? 'Lock' : 'Unlock')
                        ->action(fn (BackupController $backupController, Backup $backup, Request $request) => $backupController->toggleLock($request, $server, $backup))
                        ->visible(fn (Backup $backup) => $backup->status === BackupStatus::Successful),
                    Action::make('download')
                        ->iconSize(IconSize::Large)
                        ->color('primary')
                        ->icon('tabler-download')
                        ->authorize(fn () => auth()->user()->can(Permission::ACTION_BACKUP_DOWNLOAD, $server))
                        ->url(fn (DownloadLinkService $downloadLinkService, Backup $backup, Request $request) => $downloadLinkService->handle($backup, $request->user()), true)
                        ->visible(fn (Backup $backup) => $backup->status === BackupStatus::Successful),
                    Action::make('restore')
                        ->iconSize(IconSize::Large)
                        ->color('success')
                        ->icon('tabler-folder-up')
                        ->authorize(fn () => auth()->user()->can(Permission::ACTION_BACKUP_RESTORE, $server))
                        ->schema([
                            TextEntry::make('stop_info')
                                ->hiddenLabel()
                                ->helperText('Your server will be stopped. You will not be able to control the power state, access the file manager, or create additional backups until this process is completed.'),
                            Checkbox::make('truncate')
                                ->label('Delete all files before restoring backup?'),
                        ])
                        ->action(function (Backup $backup, $data, DaemonBackupRepository $daemonRepository, DownloadLinkService $downloadLinkService) use ($server) {
                            if (!is_null($server->status)) {
                                return Notification::make()
                                    ->danger()
                                    ->title('Backup Restore Failed')
                                    ->body('This server is not currently in a state that allows for a backup to be restored.')
                                    ->send();
                            }

                            if (!$backup->is_successful && is_null($backup->completed_at)) {
                                return Notification::make()
                                    ->danger()
                                    ->title('Backup Restore Failed')
                                    ->body('This backup cannot be restored at this time: not completed or failed.')
                                    ->send();
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

                            return Notification::make()
                                ->title('Restoring Backup')
                                ->send();
                        })
                        ->visible(fn (Backup $backup) => $backup->status === BackupStatus::Successful),
                    DeleteAction::make('delete')
                        ->iconSize(IconSize::Large)
                        ->disabled(fn (Backup $backup) => $backup->is_locked)
                        ->modalDescription(fn (Backup $backup) => 'Do you wish to delete ' . $backup->name . '?')
                        ->modalSubmitActionLabel('Delete Backup')
                        ->action(function (Backup $backup, DeleteBackupService $deleteBackupService) {
                            try {
                                $deleteBackupService->handle($backup);
                            } catch (ConnectionException) {
                                Notification::make()
                                    ->title('Could not delete backup')
                                    ->body('Connection to node failed')
                                    ->danger()
                                    ->send();

                                return;
                            }

                            Activity::event('server:backup.delete')
                                ->subject($backup)
                                ->property(['name' => $backup->name, 'failed' => !$backup->is_successful])
                                ->log();
                        })
                        ->visible(fn (Backup $backup) => $backup->status !== BackupStatus::InProgress),
                ])->iconSize(IconSize::ExtraLarge),
            ])
            ->toolbarActions([
                CreateAction::make()
                    ->authorize(fn () => auth()->user()->can(Permission::ACTION_BACKUP_CREATE, $server))
                    ->icon('tabler-file-zip')
                    ->tooltip(fn () => $server->backups()->count() >= $server->backup_limit ? 'Backup Limit Reached' : 'Create Backup')
                    ->disabled(fn () => $server->backups()->count() >= $server->backup_limit)
                    ->color(fn () => $server->backups()->count() >= $server->backup_limit ? 'danger' : 'primary')
                    ->createAnother(false)
                    ->hiddenLabel()->iconButton()->iconSize(IconSize::ExtraLarge)
                    ->action(function (InitiateBackupService $initiateBackupService, $data) use ($server) {
                        $action = $initiateBackupService->setIgnoredFiles(explode(PHP_EOL, $data['ignored'] ?? ''));

                        if (auth()->user()->can(Permission::ACTION_BACKUP_DELETE, $server)) {
                            $action->setIsLocked((bool) $data['is_locked']);
                        }

                        try {
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
                        } catch (HttpException $e) {
                            return Notification::make()
                                ->danger()
                                ->title('Backup Failed')
                                ->body($e->getMessage() . ' Try again' . ($e->getHeaders()['Retry-After'] ? ' in ' . $e->getHeaders()['Retry-After'] . ' seconds.' : ''))
                                ->send();
                        }
                    }),
            ]);
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can(Permission::ACTION_BACKUP_READ, Filament::getTenant());
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can(Permission::ACTION_BACKUP_CREATE, Filament::getTenant());
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_BACKUP_DELETE, Filament::getTenant());
    }

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [
            'index' => ListBackups::route('/'),
        ];
    }
}
