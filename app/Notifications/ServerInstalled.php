<?php

namespace App\Notifications;

use App\Filament\Server\Pages\Console;
use App\Models\Server;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServerInstalled extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Server $server) {}

    /** @return string[] */
    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage())
            ->greeting('Hello ' . $notifiable->username . '.')
            ->line('Your server has finished installing and is now ready for you to use.')
            ->line('Server Name: ' . $this->server->name)
            ->action('Login and Begin Using', Console::getUrl(panel: 'server', tenant: $this->server));
    }
}
