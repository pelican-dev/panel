<?php

namespace App\Listeners\Server;

use App\Events\Server\SubUserAdded;
use App\Filament\Server\Pages\Console;
use App\Notifications\AddedToServer;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class SubUserAddedListener
{
    public function handle(SubUserAdded $event): void
    {
        $event->subuser->loadMissing('server');
        $event->subuser->loadMissing('user');

        Notification::make()
            ->title(trans('notifications.user_added.title', locale: $event->subuser->user->language))
            ->body(trans('notifications.user_added.body', ['server' => $event->subuser->server->name], $event->subuser->user->language))
            ->actions([
                Action::make('view')
                    ->button()
                    ->label('notifications.open_server')
                    ->translateLabel()
                    ->markAsRead()
                    ->url(fn () => Console::getUrl(panel: 'server', tenant: $event->subuser->server)),
            ])
            ->sendToDatabase($event->subuser->user);

        $event->subuser->user->notify(new AddedToServer($event->subuser->server));
    }
}
