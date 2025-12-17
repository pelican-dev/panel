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

        $locale = $event->subuser->user->language ?? 'en';

        Notification::make()
            ->title(trans('notifications.user_added.title', locale: $locale))
            ->body(trans('notifications.user_added.body', ['server' => $event->subuser->server->name], $locale))
            ->actions([
                Action::make('view')
                    ->button()
                    ->label(trans('notifications.open_server', locale: $locale))
                    ->markAsRead()
                    ->url(fn () => Console::getUrl(panel: 'server', tenant: $event->subuser->server)),
            ])
            ->sendToDatabase($event->subuser->user);

        $event->subuser->user->notify(new AddedToServer($event->subuser->server));
    }
}
