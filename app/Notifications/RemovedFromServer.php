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

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage())
            ->error()
            ->greeting('Hello ' . $notifiable->username . '.')
            ->line('You have been removed as a subuser for the following server.')
            ->line('Server Name: ' . $this->server->name)
            ->action('Visit Panel', url(''));
    }
}
