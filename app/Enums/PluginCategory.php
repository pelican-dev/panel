<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PluginCategory: string implements HasIcon, HasLabel
{
    case Plugin = 'plugin';
    case Theme = 'theme';
    case Language = 'language';

    public function getIcon(): BackedEnum
    {
        return match ($this) {
            self::Plugin => TablerIcon::Package,
            self::Theme => TablerIcon::Palette,
            self::Language => TablerIcon::Language,
        };
    }

    public function getLabel(): string
    {
        return trans('admin/plugin.category_enum.' . $this->value);
    }
}
