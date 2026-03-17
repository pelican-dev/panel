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

    public function toMail(): MailMessage
    {
        $locale = $this->user->language ?? 'en';

        return (new MailMessage())
            ->subject(trans('mail.mail_tested.subject', locale: $locale))
            ->greeting(trans('mail.greeting', ['name' => $this->user->username], $locale))
            ->line(trans('mail.mail_tested.body', locale: $locale));
    }
}
