<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Widgets\ServerListEntryWidget;
use Filament\Pages\Page;
use Filament\Widgets\WidgetConfiguration;

class ServerList extends Page
{
    protected static string $routePath = '/';

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament-panels::pages.dashboard';

    public static function getRoutePath(): string
    {
        return static::$routePath;
    }

    public function getWidgets(): array
    {
        $servers = [];
        foreach (auth()->user()->accessibleServers()->get() as $server) {
            $servers[] = new WidgetConfiguration(ServerListEntryWidget::class, ['server' => $server]);
        }

        return $servers;
    }

    public function getVisibleWidgets(): array
    {
        return $this->filterVisibleWidgets($this->getWidgets());
    }

    public function getColumns(): int|string|array
    {
        return 2;
    }
}
