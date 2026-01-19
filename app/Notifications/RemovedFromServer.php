<?php

namespace App\Notifications;

use App\Models\Server;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RemovedFromServer extends Notification implements ShouldQueue
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
            ->error()
            ->greeting(__('notifications.user_removed.greeting', ['username' => $notifiable->username]))
            ->line(__('notifications.user_removed.body'))
            ->line(__('notifications.user_removed.server_name', ['server_name' => $this->server->name]))
            ->action(__('notifications.user_removed.action'), url(''));
    }
}
