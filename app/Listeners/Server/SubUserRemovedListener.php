<?php

namespace App\Listeners\Server;

use App\Events\Server\SubUserRemoved;
use App\Notifications\RemovedFromServer;
use Filament\Notifications\Notification;

class SubUserRemovedListener
{
    public function handle(SubUserRemoved $event): void
    {
        $locale = $event->user->language ?? 'en';

        Notification::make()
            ->title(trans('notifications.user_removed.title', locale: $locale))
            ->body(trans('notifications.user_removed.body', ['server' => $event->server->name], $locale))
            ->sendToDatabase($event->user);

        $event->user->notify(new RemovedFromServer($event->server));
    }
}
