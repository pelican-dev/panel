<?php

namespace App\Enums;

enum CustomizationKey: string
{
    case ConsoleRows = 'console_rows';
    case ConsoleFont = 'console_font';
    case ConsoleFontSize = 'console_font_size';
    case ConsoleGraphPeriod = 'console_graph_period';
    case TopNavigation = 'top_navigation';
    case DashboardLayout = 'dashboard_layout';

    public function getDefaultValue(): string|int|bool
    {
        return match ($this) {
            self::ConsoleRows => 30,
            self::ConsoleFont => 'monospace',
            self::ConsoleFontSize => 14,
            self::ConsoleGraphPeriod => 30,
            self::TopNavigation => config('panel.filament.default-navigation', 'sidebar'),
            self::DashboardLayout => 'grid',
        };
    }

    /** @return array<string, string|int|bool> */
    public static function getDefaultCustomization(): array
    {
        $default = [];

        foreach (self::cases() as $key) {
            $default[$key->value] = $key->getDefaultValue();
        }

        return $default;
    }
}
