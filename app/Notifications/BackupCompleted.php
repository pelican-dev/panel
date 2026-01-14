<?php

namespace App\Notifications;

use App\Filament\Server\Resources\Backups\Pages\ListBackups;
use App\Models\Backup;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BackupCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Backup $backup) {}

    /** @return string[] */
    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage())
            ->greeting('Hello ' . $notifiable->username . '.')
            ->line('Your backup has finished and is now ready.')
            ->line('Backup Name: ' . $this->backup->name)
            ->line('Server Name: ' . $this->backup->server->name)
            ->line('Size: ' . convert_bytes_to_readable($this->backup->bytes))
            ->action('View Backups', ListBackups::getUrl(panel: 'server', tenant: $this->backup->server));
    }
}
