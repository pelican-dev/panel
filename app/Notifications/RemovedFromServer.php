<?php

namespace App\Notifications;

use App\Events\Server\SubUserRemoved;
use App\Models\Server;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Container\Container;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RemovedFromServer extends Notification implements ShouldQueue
{
    use Queueable;

    public Server $server;

    public User $user;

    /**
     * Handle a direct call to this notification from the subuser removed event. This is configured
     * in the event service provider.
     */
    public function handle(SubUserRemoved $event): void
    {
        $this->server = $event->server;
        $this->user = $event->user;

        // Since we are calling this notification directly from an event listener we need to fire off the dispatcher
        // to send the email now. Don't use send() or you'll end up firing off two different events.
        Container::getInstance()->make(Dispatcher::class)->sendNow($this->user, $this);
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->error()
            ->greeting('Hello ' . $this->user->username . '.')
            ->line('You have been removed as a subuser for the following server.')
            ->line('Server Name: ' . $this->server->name)
            ->action('Visit Panel', route('index'));
    }
}
