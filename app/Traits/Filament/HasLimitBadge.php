<?php

namespace App\Traits\Filament;

trait HasLimitBadge
{
    public const WARNING_THRESHOLD = 0.7;

    protected static function getBadgeCount(): int
    {
        return 0;
    }

    protected static function getBadgeLimit(): int
    {
        return 0;
    }

    public static function getNavigationBadge(): string
    {
        $limit = static::getBadgeLimit();
        $count = static::getBadgeCount();

        return $count . ($limit === 0 ? '' : ' / ' . $limit);
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $limit = static::getBadgeLimit();
        $count = static::getBadgeCount();

        if ($limit === 0) {
            return null;
        }

        return $count >= $limit ? 'danger' : ($count >= $limit * self::WARNING_THRESHOLD ? 'warning' : 'success');
    }
}
