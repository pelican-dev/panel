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
    case Incompatible = 'incompatible';

    public function getIcon(): string
    {
        return match ($this) {
            self::NotInstalled => 'tabler-heart-off',
            self::Disabled => 'tabler-heart-x',
            self::Enabled => 'tabler-heart-check',
            self::Errored => 'tabler-heart-broken',
            self::Incompatible => 'tabler-heart-cancel',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::NotInstalled => 'gray',
            self::Disabled => 'warning',
            self::Enabled => 'success',
            self::Errored => 'danger',
            self::Incompatible => 'danger',
        };
    }

    public function getLabel(): string
    {
        return trans('admin/plugin.status_enum.' . $this->value);
    }
}
