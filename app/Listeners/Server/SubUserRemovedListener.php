<?php

namespace App\Listeners\Server;

use App\Events\Server\SubUserRemoved;
use Filament\Notifications\Notification;

class SubUserRemovedListener
{
    public function handle(SubUserRemoved $event): void
    {
        Notification::make()
            ->title('Removed from Server')
            ->body('You have been removed as a subuser from ' . $event->server->name . '.')
            ->sendToDatabase($event->user);
    }
}
