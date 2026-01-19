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

    public function locale(User $notifiable): string
    {
        return $notifiable->language ?? 'en';
    }

    public function toMail(User $notifiable): MailMessage
    {
        $message = (new MailMessage())
            ->greeting(__('notifications.account_created.greeting', ['username' => $notifiable->username]))
            ->line(__('notifications.account_created.body', ['app_name' => config('app.name')]))
            ->line(__('notifications.account_created.username', ['username' => $notifiable->username]))
            ->line(__('notifications.account_created.email', ['email' => $notifiable->email]));

        if (!is_null($this->token)) {
            return $message->action(__('notifications.account_created.action'), Filament::getPanel('app')->getResetPasswordUrl($this->token, $notifiable));
        }

        return $message;
    }
}
