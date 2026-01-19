<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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

    public function locale(User $notifiable): string
    {
        return $notifiable->language ?? 'en';
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(__('notifications.mail_tested.subject'))
            ->greeting(__('notifications.mail_tested.greeting', ['username' => $notifiable->username]))
            ->line(__('notifications.mail_tested.body'));
    }
}
