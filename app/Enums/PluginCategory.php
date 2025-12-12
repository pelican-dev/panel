<?php

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PluginCategory: string implements HasIcon, HasLabel
{
    case Plugin = 'plugin';
    case Theme = 'theme';
    case Language = 'language';

    public function getIcon(): string
    {
        return match ($this) {
            self::Plugin => 'tabler-package',
            self::Theme => 'tabler-palette',
            self::Language => 'tabler-language',
        };
    }

    public function getLabel(): string
    {
        return trans('admin/plugin.category_enum.' . $this->value);
    }
}
