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

    public function locale(User $notifiable): string
    {
        return $notifiable->language ?? 'en';
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage())
            ->greeting(__('notifications.server_installed.greeting', ['username' => $notifiable->username]))
            ->line(__('notifications.server_installed.body'))
            ->line(__('notifications.server_installed.server_name', ['server_name' => $this->server->name]))
            ->action(__('notifications.server_installed.action'), Console::getUrl(panel: 'server', tenant: $this->server));
    }
}
