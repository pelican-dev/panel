<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Filament\App\Resources\ServerResource\Pages\ListServers;

class ServerInstalled extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Server $server)
    {
    }

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
            ->action('Login and Begin Using', ListServers::getUrl());
    }
}
