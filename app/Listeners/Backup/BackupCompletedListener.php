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
        $event->backup->loadMissing(['server', 'server.user']);

        $user = $event->backup->server->user;
        $locale = $user->language ?? 'en';

        // Always send panel notification
        Notification::make()
            ->status($event->successful ? 'success' : 'danger')
            ->title(trans('notifications.backup_' . ($event->successful ? 'completed' : 'failed'), locale: $locale))
            ->body(trans('notifications.backup_body', ['name' => $event->backup->name, 'server' => $event->backup->server->name], $locale))
            ->actions([
                Action::make('view')
                    ->button()
                    ->label(trans('notifications.view_backups', locale: $locale))
                    ->markAsRead()
                    ->url(fn () => ListBackups::getUrl(panel: 'server', tenant: $event->backup->server)),
            ])
            ->sendToDatabase($user);

        // Send email notification if enabled and backup was successful
        if ($event->successful && config()->get('panel.email.send_backup_notification', true)) {
            $user->notify(new BackupCompletedNotification($event->backup));
        }
    }
}
