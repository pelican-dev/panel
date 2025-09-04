<?php

namespace App\Listeners\Server;

use App\Events\Server\SubUserRemoved;
use App\Notifications\RemovedFromServer;
use Filament\Notifications\Notification;

class SubUserRemovedListener
{
    public function handle(SubUserRemoved $event): void
    {
        Notification::make()
            ->title(trans('notifications.user_removed.title'))
            ->body(trans('notifications.user_removed.body', ['server' => $event->server->name]))
            ->sendToDatabase($event->user);

        $event->user->notify(new RemovedFromServer($event->server));
    }
}
