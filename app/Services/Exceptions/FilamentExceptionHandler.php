<?php

namespace App\Services\Exceptions;

use Exception;
use Filament\Notifications\Notification;

class FilamentExceptionHandler
{
    public function handle(Exception $exception, callable $stopPropagation): void
    {
        Notification::make()
            ->title($exception->title ?? null)
            ->body($exception->body ?? $exception->getMessage())
            ->color($exception->color ?? 'danger')
            ->icon($exception->icon ?? 'tabler-x')
            ->danger()
            ->send();

        if ($this->stopPropagation ?? true) {
            $stopPropagation();
        }
    }
}
