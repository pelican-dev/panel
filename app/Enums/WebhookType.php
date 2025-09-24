<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum WebhookType: string implements HasColor, HasIcon, HasLabel
{
    case Regular = 'regular';
    case Discord = 'discord';

    public function getLabel(): string
    {
        return trans('admin/webhook.' . $this->value);
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::Regular => null,
            self::Discord => 'blurple',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Regular => 'tabler-world-www',
            self::Discord => 'tabler-brand-discord',
        };
    }
}
