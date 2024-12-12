<?php

namespace App\Listeners\Server;

use App\Events\Server\Installed;
use App\Filament\Server\Pages\Console;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class ServerInstalledListener
{
    public function handle(Installed $event): void
    {
        $event->server->loadMissing('user');

        Notification::make()
            ->status($event->successful ? 'success' : 'danger')
            ->title('Server ' . ($event->initialInstall ? 'Installation' : 'Reinstallation') . ' ' . ($event->successful ? 'completed' : 'failed'))
            ->body('Server Name: ' . $event->server->name)
            ->actions([
                Action::make('view')
                    ->button()
                    ->label('Open Server')
                    ->markAsRead()
                    ->url(fn () => Console::getUrl(panel: 'server', tenant: $event->server)),
            ])
            ->sendToDatabase($event->server->user);
    }
}
