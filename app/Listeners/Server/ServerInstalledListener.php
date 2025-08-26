<?php

namespace App\Listeners\Server;

use App\Events\Server\Installed;
use App\Filament\Server\Pages\Console;
use App\Notifications\ServerInstalled;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class ServerInstalledListener
{
    public function handle(Installed $event): void
    {
        $event->server->loadMissing('user');

        Notification::make()
            ->status($event->successful ? 'success' : 'danger')
            ->title(trans('notifications.') . ($event->initialInstall ? 'installation' : 'reinstallation') . ' ' . trans('notifications.') . ($event->successful ? 'completed' : 'failed'))
            ->body(trans('server/setting.server_info.name') . ': ' . $event->server->name)
            ->actions([
                Action::make('view')
                    ->button()
                    ->label(trans('notifications.open_server'))
                    ->markAsRead()
                    ->url(fn () => Console::getUrl(panel: 'server', tenant: $event->server)),
            ])
            ->sendToDatabase($event->server->user);

        if (($event->initialInstall && config()->get('panel.email.send_install_notification', true)) ||
            (!$event->initialInstall && config()->get('panel.email.send_reinstall_notification', true))) {
            $event->server->user->notify(new ServerInstalled($event->server));
        }
    }
}
