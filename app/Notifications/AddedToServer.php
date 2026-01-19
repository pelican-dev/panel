<?php

namespace App\Notifications;

use App\Filament\Server\Pages\Console;
use App\Models\Server;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddedToServer extends Notification implements ShouldQueue
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
            ->greeting(__('notifications.user_added.greeting', ['username' => $notifiable->username]))
            ->line(__('notifications.user_added.body'))
            ->line(__('notifications.user_added.server_name', ['server_name' => $this->server->name]))
            ->action(__('notifications.user_added.action'), Console::getUrl(panel: 'server', tenant: $this->server));
    }
}
