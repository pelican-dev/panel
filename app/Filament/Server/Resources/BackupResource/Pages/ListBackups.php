<?php

namespace App\Filament\Server\Resources\BackupResource\Pages;

use App\Facades\Activity;
use App\Filament\Server\Resources\BackupResource;
use App\Models\Permission;
use App\Models\Server;
use App\Services\Backups\InitiateBackupService;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ListBackups extends ListRecords
{
    protected static string $resource = BackupResource::class;

    protected function getHeaderActions(): array
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return [
            CreateAction::make()
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_BACKUP_CREATE, $server))
                ->label(fn () => $server->backups()->count() >= $server->backup_limit ? 'Backup limit reached' : 'Create Backup')
                ->disabled(fn () => $server->backups()->count() >= $server->backup_limit)
                ->color(fn () => $server->backups()->count() >= $server->backup_limit ? 'danger' : 'primary')
                ->createAnother(false)
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
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
