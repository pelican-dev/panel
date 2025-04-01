<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PluginStatus: string implements HasColor, HasIcon, HasLabel
{
    case Disabled = 'disabled';
    case Enabled = 'enabled';
    case Errored = 'errored';

    public function getIcon(): string
    {
        return match ($this) {
            self::Disabled => 'tabler-heart-off',
            self::Enabled => 'tabler-heart-check',
            self::Errored => 'tabler-heart-x',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Disabled => 'gray',
            self::Enabled => 'success',
            self::Errored => 'danger',
        };
    }

    public function getLabel(): string
    {
        return $this->name;
    }
}
