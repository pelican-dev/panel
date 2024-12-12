<?php

namespace App\Listeners\Server;

use App\Events\Server\SubUserRemoved;
use App\Filament\Server\Pages\Console;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class SubUserRemovedListener
{
    public function handle(SubUserRemoved $event): void
    {
        Notification::make()
            ->title('Removed from Server')
            ->body('You have been removed as a subuser from ' . $event->server->name . '.')
            ->actions([
                Action::make('view')
                    ->button()
                    ->label('Open Server')
                    ->markAsRead()
                    ->url(fn () => Console::getUrl(panel: 'server', tenant: $event->server)),
            ])
            ->sendToDatabase($event->user);
    }
}
