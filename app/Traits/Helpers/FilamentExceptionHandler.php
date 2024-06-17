<?php

namespace App\Traits\Helpers;

use Filament\Notifications\Notification;

trait FilamentExceptionHandler
{
    public function exception($exception, $stopPropagation): void
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
