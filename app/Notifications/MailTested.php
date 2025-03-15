<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MailTested extends Notification
{
    public function __construct(private User $user) {}

    /**
     * @return string[]
     */
    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->subject('Panel Test Message')
            ->greeting('Hello ' . $this->user->name . '!')
            ->line('This is a test of the Panel mail system. You\'re good to go!');
    }
}
