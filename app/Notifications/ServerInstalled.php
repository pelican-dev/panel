<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Models\Server;
use Illuminate\Container\Container;
use App\Events\Server\Installed;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Notifications\Messages\MailMessage;

class ServerInstalled extends Notification implements ShouldQueue
{
    use Queueable;

    public Server $server;

    public User $user;

    /**
     * Handle a direct call to this notification from the server installed event. This is configured
     * in the event service provider.
     */
    public function handle(Installed $event): void
    {
        if ($event->initialInstall && !config()->get('panel.email.send_install_notification', true)) {
            return;
        }

        if (!$event->initialInstall && !config()->get('panel.email.send_reinstall_notification', true)) {
            return;
        }

        if ($event->successful) {
            $event->server->loadMissing('user');

            $this->server = $event->server;
            $this->user = $event->server->user;

            // Since we are calling this notification directly from an event listener we need to fire off the dispatcher
            // to send the email now. Don't use send() or you'll end up firing off two different events.
            Container::getInstance()->make(Dispatcher::class)->sendNow($this->user, $this);
        }
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
            ->greeting('Hello ' . $this->user->username . '.')
            ->line('Your server has finished installing and is now ready for you to use.')
            ->line('Server Name: ' . $this->server->name)
            ->action('Login and Begin Using', route('index'));
    }
}
