<?php

namespace App\Traits\Filament;

use Filament\Resources\Pages\PageRegistration;

trait CanCustomizePages
{
    /** @var array<string, PageRegistration> */
    protected static array $customPages = [];

    /** @param array<string, PageRegistration> $customPages */
    public static function registerCustomPages(array $customPages): void
    {
        static::$customPages = array_merge(static::$customPages, $customPages);
    }

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [];
    }

    /** @return array<string, PageRegistration> */
    public static function getPages(): array
    {
        return array_unique(array_merge(static::$customPages, static::getDefaultPages()), SORT_REGULAR);
    }
}
