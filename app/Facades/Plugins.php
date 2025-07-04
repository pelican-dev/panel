<?php

namespace App\Facades;

use App\Models\Plugin;
use Illuminate\Support\Facades\Facade;
use App\Services\Helpers\PluginService;
use Filament\Panel;

/**
 * @method static void loadPlugins()
 * @method static void loadPanelPlugins(Panel $panel)
 * @method static void requireComposerPackages(Plugin $plugin)
 * @method static void runPluginMigrations(Plugin $plugin)
 * @method static void installPlugin(Plugin $plugin)
 * @method static void enablePlugin(string|Plugin $plugin)
 * @method static void disablePlugin(string|Plugin $plugin)
 * @method static void updateLoadOrder(array $order)
 *
 * @see \App\Services\Helpers\PluginService
 */
class Plugins extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PluginService::class;
    }
}
