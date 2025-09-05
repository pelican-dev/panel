<?php

namespace App\Filament\Server\Resources\BackupResource\Pages;

use App\Facades\Activity;
use App\Filament\Server\Resources\BackupResource;
use App\Models\Permission;
use App\Models\Server;
use App\Services\Backups\InitiateBackupService;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconSize;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ListBackups extends ListRecords
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = BackupResource::class;

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return [
            CreateAction::make()
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_BACKUP_CREATE, $server))
                ->icon('tabler-file-zip')->iconButton()->iconSize(IconSize::Large)
                ->disabled(fn () => $server->backups()->count() >= $server->backup_limit)
                ->tooltip(fn () => $server->backups()->count() >= $server->backup_limit ? trans('server/backup.actions.create.limit') : trans('server/backup.actions.create.title'))
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
                            ->title(trans('server/backup.actions.create.notification_success'))
                            ->body(trans('server/backup.actions.create.created', ['name' => $backup->name]))
                            ->success()
                            ->send();
                    } catch (HttpException $e) {
                        return Notification::make()
                            ->title(trans('server/backup.actions.create.notification_fail'))
                            ->body($e->getMessage() . ' Try again' . ($e->getHeaders()['Retry-After'] ? ' in ' . $e->getHeaders()['Retry-After'] . ' seconds.' : ''))
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return trans('server/backup.title');
    }
}
