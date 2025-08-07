<?php

namespace App\Facades;

use App\Models\Plugin;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Facade;
use App\Services\Helpers\PluginService;
use Filament\Panel;

/**
 * @method static void loadPlugins()
 * @method static void loadPanelPlugins(Panel $panel)
 * @method static void requireComposerPackages(Plugin $plugin)
 * @method static void runPluginMigrations(Plugin $plugin)
 * @method static void installPlugin(Plugin $plugin, bool $enable = true)
 * @method static void updatePlugin(Plugin $plugin)
 * @method static void downloadPluginFromFile(UploadedFile $file)
 * @method static void downloadPluginFromUrl(string $url)
 * @method static void enablePlugin(string|Plugin $plugin)
 * @method static void disablePlugin(string|Plugin $plugin)
 * @method static void updateLoadOrder(array<int, string> $order)
 * @method static bool hasThemePluginEnabled()
 * @method static string[] getPluginLanguages()
 * @method static bool isDevModeActive()
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
