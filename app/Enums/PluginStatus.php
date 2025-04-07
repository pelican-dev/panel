<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PluginStatus: string implements HasColor, HasIcon, HasLabel
{
    case NotInstalled = 'not_installed';
    case Disabled = 'disabled';
    case Enabled = 'enabled';
    case Errored = 'errored';

    public function getIcon(): string
    {
        return match ($this) {
            self::NotInstalled => 'tabler-heart-off',
            self::Disabled => 'tabler-heart-x',
            self::Enabled => 'tabler-heart-check',
            self::Errored => 'tabler-heart-broken',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::NotInstalled => 'gray',
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
