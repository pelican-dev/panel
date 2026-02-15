<?php

namespace App\Livewire;

use Filament\Notifications\Collection;

class AlertBannerCollection extends Collection
{
    public static function fromLivewire($value): static
    {
        return app(static::class, ['items' => $value])->transform(
            fn (array $alertBanner): AlertBanner => AlertBanner::fromArray($alertBanner),
        );
    }
}
