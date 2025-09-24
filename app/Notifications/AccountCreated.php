<?php

namespace App\Notifications;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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
            ->greeting('Hello ' . $notifiable->username . '!')
            ->line('You are receiving this email because an account has been created for you on ' . config('app.name') . '.')
            ->line('Username: ' . $notifiable->username)
            ->line('Email: ' . $notifiable->email);

        if (!is_null($this->token)) {
            return $message->action('Setup Your Account', Filament::getPanel('app')->getResetPasswordUrl($this->token, $notifiable));
        }

        return $message;
    }
}
