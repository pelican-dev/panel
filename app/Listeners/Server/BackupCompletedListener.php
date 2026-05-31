<?php

namespace App\Listeners\Server;

use App\Events\Server\BackupCompleted;
use App\Filament\Server\Resources\Backups\Pages\ListBackups;
use App\Notifications\BackupCompleted as BackupCompletedNotification;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class BackupCompletedListener
{
    public function handle(BackupCompleted $event): void
    {
        $event->backup->loadMissing('server');
        $event->backup->server->loadMissing('user');

        $locale = $event->backup->server->user->language ?? 'en';

        Notification::make()
            ->status($event->backup->is_successful ? 'success' : 'danger')
            ->title(trans('notifications.backup_' . ($event->backup->is_successful ? 'completed' : 'failed'), locale: $locale))
            ->body(trans('notifications.backup_body', ['name' => $event->backup->name, 'server' => $event->backup->server->name], $locale))
            ->actions([
                Action::make('exclude_view')
                    ->button()
                    ->label(trans('notifications.view_backups', locale: $locale))
                    ->markAsRead()
                    ->url(fn () => ListBackups::getUrl(panel: 'server', tenant: $event->backup->server)),
            ])
            ->sendToDatabase($event->backup->server->user);

        if (config()->get('panel.email.send_backup_completed_notification', true)) {
            $event->backup->server->user->notify(new BackupCompletedNotification($event->backup));
        }
    }
}
