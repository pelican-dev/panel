<?php

namespace App\Services\Helpers;

use App\Enums\PluginStatus;
use App\Models\Plugin;
use Composer\Autoload\ClassLoader;
use Exception;
use Filament\Panel;
use Illuminate\Filesystem\Filesystem;

class PluginService
{
    public function __construct(private Filesystem $fileSystem)
    {
    }

    public function loadPlugins(): void
    {
        // Don't load any plugins during tests
        if (app()->runningUnitTests()) {
            return;
        }

        /** @var ClassLoader $classLoader */
        $classLoader = $this->fileSystem->getRequire(base_path('vendor/autoload.php'));

        $plugins = Plugin::all();
        foreach ($plugins as $plugin) {
            if ($plugin->isDisabled()) {
                continue;
            }

            try {
                if (!array_key_exists($plugin->namespace, $classLoader->getClassMap())) {
                    $classLoader->setPsr4($plugin->namespace . '\\', plugin_path($plugin->id, 'src/'));
                }

                foreach ($plugin->getProviders() as $provider) {
                    if (is_string($provider) && !class_exists($provider)) {
                        throw new Exception('Provider class "' . $provider . '" not found');
                    }

                    app()->register($provider);
                }
            } catch (Exception $exception) {
                report($exception);

                $this->setStatus($plugin, PluginStatus::Errored, $exception->getMessage());
            }
        }
    }

    public function loadPanelPlugins(Panel $panel): void
    {
        // Don't load any plugins during tests
        if (app()->runningUnitTests()) {
            return;
        }

        $plugins = Plugin::all();
        foreach ($plugins as $plugin) {
            if (!$plugin->shouldLoad($panel->getId())) {
                continue;
            }

            try {
                $pluginClass = $plugin->fullClass();

                if (!class_exists($pluginClass)) {
                    throw new Exception('Class "' . $pluginClass . '" not found');
                }

                $panel->plugin($pluginClass::make());

                $this->enablePlugin($plugin);
            } catch (Exception $exception) {
                report($exception);

                $this->setStatus($plugin, PluginStatus::Errored, $exception->getMessage());
            }
        }
    }

    public function enablePlugin(string|Plugin $plugin): void
    {
        $this->setStatus($plugin, PluginStatus::Enabled);
    }

    public function disablePlugin(string|Plugin $plugin): void
    {
        $this->setStatus($plugin, PluginStatus::Disabled);
    }

    public function getStatus(string|Plugin $plugin): PluginStatus
    {
        $plugin = $plugin instanceof Plugin ? $plugin->id : $plugin;
        $data = $this->fileSystem->json(plugin_path($plugin, 'plugin.json'), JSON_THROW_ON_ERROR);

        return $data['status'] ?? PluginStatus::Errored;
    }

    private function setStatus(string|Plugin $plugin, PluginStatus $status, ?string $message = null): void
    {
        $plugin = $plugin instanceof Plugin ? $plugin->id : $plugin;
        $path = plugin_path($plugin, 'plugin.json');

        $data = $this->fileSystem->json($path, JSON_THROW_ON_ERROR);
        $data['status'] = $status;
        $data['status_message'] = $message;

        $this->fileSystem->put($path, json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
