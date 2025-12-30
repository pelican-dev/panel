<?php

namespace App\Listeners\Server;

use App\Events\Server\Installed;
use App\Filament\Server\Pages\Console;
use App\Notifications\ServerInstalled;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ServerInstalledListener
{
    public function handle(Installed $event): void
    {
        $event->server->loadMissing('user');

        $locale = $event->server->user->language ?? 'en';

        Notification::make()
            ->status($event->successful ? 'success' : 'danger')
            ->title(trans('notifications.' . ($event->initialInstall ? 'installation' : 'reinstallation') . '_' . ($event->successful ? 'completed' : 'failed'), locale: $locale))
            ->body(trans('server/setting.server_info.server_name', ['name' => $event->server->name], $locale))
            ->actions([
                Action::make('view')
                    ->button()
                    ->label(trans('notifications.open_server', locale: $locale))
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
