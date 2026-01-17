<?php

namespace App\Listeners\Backup;

use App\Events\Backup\BackupCompleted;
use App\Filament\Server\Resources\Backups\Pages\ListBackups;
use App\Notifications\BackupCompleted as BackupCompletedNotification;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class BackupCompletedListener
{
    public function handle(BackupCompleted $event): void
    {
        $event->backup->loadMissing('server');
        $event->owner->loadMissing('language');

        $locale = $event->owner->language ?? 'en';

        Notification::make()
            ->success()
            ->title(trans('notifications.backup_completed', locale: $locale))
            ->body(trans('notifications.backup_name_and_server', [
                'backup' => $event->backup->name,
                'server' => $event->server->name,
            ], $locale))
            ->actions([
                Action::make('view')
                    ->button()
                    ->label(trans('notifications.view_backups', locale: $locale))
                    ->markAsRead()
                    ->url(fn () => ListBackups::getUrl(panel: 'server', tenant: $event->server)),
            ])
            ->sendToDatabase($event->owner);

        if (config()->get('panel.email.send_backup_notification', true)) {
            $event->owner->notify(new BackupCompletedNotification($event->backup));
        }
    }
}
