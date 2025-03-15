<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AccountCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ?string $token = null) {}

    /** @return string[] */
    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        $message = (new MailMessage())
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You are receiving this email because an account has been created for you on ' . config('app.name') . '.')
            ->line('Username: ' . $notifiable->username)
            ->line('Email: ' . $notifiable->email);

        if (!is_null($this->token)) {
            return $message->action('Setup Your Account', url('/auth/password/reset/' . $this->token . '?email=' . urlencode($notifiable->email)));
        }

        return $message;
    }
}
