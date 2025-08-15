<?php

namespace App\Services\Helpers;

use App\Enums\PluginStatus;
use App\Exceptions\Service\InvalidFileUploadException;
use App\Models\Plugin;
use Composer\Autoload\ClassLoader;
use Exception;
use Filament\Panel;
use Illuminate\Console\Application as ConsoleApplication;
use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\ServiceProvider;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use ZipArchive;

class PluginService
{
    public function __construct(private Application $app, private Composer $composer) {}

    public function loadPlugins(): void
    {
        // Don't load any plugins during tests
        if ($this->app->runningUnitTests()) {
            return;
        }

        /** @var ClassLoader $classLoader */
        $classLoader = File::getRequire(base_path('vendor/autoload.php'));

        $plugins = Plugin::query()->orderBy('load_order')->get();
        foreach ($plugins as $plugin) {
            try {
                // Filter out plugins that are not compatible with the current panel version
                if (!$plugin->isCompatible()) {
                    $this->setStatus($plugin, PluginStatus::Incompatible, 'This Plugin is only compatible with Panel version ' . $plugin->panel_version . (!$plugin->isPanelVersionStrict() ? ' or newer' : '') . ' but you are using version ' . config('app.version') . '!');

                    continue;
                } else {
                    // Make sure to update the status if a plugin is no longer incompatible (e.g. because the user changed their panel version)
                    if ($plugin->isIncompatible()) {
                        $this->disablePlugin($plugin);
                    }
                }

                // Filter out plugins that should not be loaded (e.g. because they are disabled or not installed yet)
                if (!$plugin->shouldLoad()) {
                    continue;
                }

                // Load config
                $config = plugin_path($plugin->id, 'config', $plugin->id . '.php');
                if (file_exists($config)) {
                    config()->set($plugin->id, require $config);
                }

                // Load translations
                $translations = plugin_path($plugin->id, 'lang');
                if (file_exists($translations)) {
                    $this->app->afterResolving('translator', function ($translator) use ($plugin, $translations) {
                        if ($plugin->isLanguage()) {
                            $translator->addPath($translations);
                        } else {
                            $translator->addNamespace($plugin->id, $translations);
                        }
                    });
                }

                // Autoload src directory
                if (!array_key_exists($plugin->namespace, $classLoader->getClassMap())) {
                    $classLoader->setPsr4($plugin->namespace . '\\', plugin_path($plugin->id, 'src/'));
                }

                // Register service providers
                foreach ($plugin->getProviders() as $provider) {
                    if (!class_exists($provider) || !is_subclass_of($provider, ServiceProvider::class)) {
                        continue;
                    }

                    $this->app->register($provider);
                }

                // Resolve artisan commands
                foreach ($plugin->getCommands() as $command) {
                    if (!class_exists($command) || !is_subclass_of($command, Command::class)) {
                        continue;
                    }

                    ConsoleApplication::starting(function ($artisan) use ($command) {
                        $artisan->resolve($command);
                    });
                }

                // Load migrations
                $migrations = plugin_path($plugin->id, 'database', 'migrations');
                if (file_exists($migrations)) {
                    $this->app->afterResolving('migrator', function ($migrator) use ($migrations) {
                        $migrator->path($migrations);
                    });
                }

                // Load views
                $views = plugin_path($plugin->id, 'resources', 'views');
                if (file_exists($views)) {
                    $this->app->afterResolving('view', function ($view) use ($plugin, $views) {
                        $view->addNamespace($plugin->id, $views);
                    });
                }
            } catch (Exception $exception) {
                if ($this->isDevModeActive()) {
                    throw ($exception);
                }

                report($exception);

                $this->setStatus($plugin, PluginStatus::Errored, $exception->getMessage());
            }
        }
    }

    public function loadPanelPlugins(Panel $panel): void
    {
        // Don't load any plugins during tests
        if ($this->app->runningUnitTests()) {
            return;
        }

        $plugins = Plugin::query()->orderBy('load_order')->get();
        foreach ($plugins as $plugin) {
            try {
                if (!$plugin->shouldLoadPanel($panel->getId())) {
                    continue;
                }

                $pluginClass = $plugin->fullClass();

                if (!class_exists($pluginClass)) {
                    throw new Exception('Class "' . $pluginClass . '" not found');
                }

                $panel->plugin(new $pluginClass());

                $this->enablePlugin($plugin);
            } catch (Exception $exception) {
                if ($this->isDevModeActive()) {
                    throw ($exception);
                }

                report($exception);

                $this->setStatus($plugin, PluginStatus::Errored, $exception->getMessage());
            }
        }
    }

    public function requireComposerPackages(Plugin $plugin): void
    {
        if ($plugin->composer_packages) {
            $success = $this->composer->requirePackages(explode(',', $plugin->composer_packages));

            if (!$success) {
                throw new Exception("Could not require composer packages for plugin '{$plugin->id}'");
            }
        }
    }

    public function runPluginMigrations(Plugin $plugin): void
    {
        $migrations = plugin_path($plugin->id, 'database', 'migrations');
        if (file_exists($migrations)) {
            $success = Artisan::call('migrate', ['--path' => $migrations, '--force' => true]) === 0;

            if (!$success) {
                throw new Exception("Could not run migrations for plugin '{$plugin->id}'");
            }
        }
    }

    public function buildAssets(): bool
    {
        try {
            $result = Process::run('yarn install');
            if ($result->failed()) {
                throw new Exception('Could not install dependencies: ' . $result->errorOutput());
            }

            $result = Process::run('yarn build');
            if ($result->failed()) {
                throw new Exception('Could not build assets: ' . $result->errorOutput());
            }

            return true;
        } catch (Exception $exception) {
            if ($this->isDevModeActive()) {
                throw ($exception);
            }

            report($exception);
        }

        return false;
    }

    public function installPlugin(Plugin $plugin, bool $enable = true): void
    {
        try {
            $this->requireComposerPackages($plugin);

            $this->runPluginMigrations($plugin);

            $this->buildAssets();

            if ($enable) {
                $this->enablePlugin($plugin);
            }
        } catch (Exception $exception) {
            if ($this->isDevModeActive()) {
                throw ($exception);
            }

            report($exception);

            $this->setStatus($plugin, PluginStatus::Errored, $exception->getMessage());
        }
    }

    public function updatePlugin(Plugin $plugin): void
    {
        try {
            $this->downloadPluginFromUrl($plugin->getDownloadUrlForUpdate(), true);

            $this->installPlugin($plugin, false);

            cache()->forget("plugins.$plugin->id.update");
        } catch (Exception $exception) {
            if ($this->isDevModeActive()) {
                throw ($exception);
            }

            report($exception);

            $this->setStatus($plugin, PluginStatus::Errored, $exception->getMessage());
        }
    }

    public function downloadPluginFromFile(UploadedFile $file, bool $cleanDownload = false): void
    {
        $zip = new ZipArchive();

        if (!$zip->open($file->getPathname())) {
            throw new Exception('Could not open zip file.');
        }

        $pluginName = str($file->getClientOriginalName())->before('.zip')->toString();

        if ($cleanDownload) {
            File::deleteDirectory(plugin_path($pluginName));
        }

        $extractPath = $zip->locateName($pluginName) ? base_path('plugins') : plugin_path($pluginName);

        if (!$zip->extractTo($extractPath)) {
            throw new Exception('Could not extract zip file.');
        }

        $zip->close();
    }

    public function downloadPluginFromUrl(string $url, bool $cleanDownload = false): void
    {
        $info = pathinfo($url);
        $tmpDir = TemporaryDirectory::make()->deleteWhenDestroyed();
        $tmpPath = $tmpDir->path($info['basename']);

        if (!file_put_contents($tmpPath, file_get_contents($url))) {
            throw new InvalidFileUploadException('Could not write temporary file.');
        }

        $this->downloadPluginFromFile(new UploadedFile($tmpPath, $info['basename'], 'application/zip'), $cleanDownload);
    }

    public function enablePlugin(string|Plugin $plugin): void
    {
        $this->setStatus($plugin, PluginStatus::Enabled);
    }

    public function disablePlugin(string|Plugin $plugin): void
    {
        $this->setStatus($plugin, PluginStatus::Disabled);
    }

    /** @param array<string, mixed> $data */
    private function setMetaData(string|Plugin $plugin, array $data): void
    {
        $plugin = $plugin instanceof Plugin ? $plugin->id : $plugin;
        $path = plugin_path($plugin, 'plugin.json');

        if (File::exists($path)) {
            $pluginData = File::json($path, JSON_THROW_ON_ERROR);
            $metaData = array_merge($pluginData['meta'] ?? [], $data);
            $pluginData['meta'] = $metaData;

            File::put($path, json_encode($pluginData, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
    }

    private function setStatus(string|Plugin $plugin, PluginStatus $status, ?string $message = null): void
    {
        $this->setMetaData($plugin, [
            'status' => $status,
            'status_message' => $message,
        ]);
    }

    /** @param array<int, string> $order */
    public function updateLoadOrder(array $order): void
    {
        foreach ($order as $i => $plugin) {
            $this->setMetaData($plugin, [
                'load_order' => $i,
            ]);
        }
    }

    public function hasThemePluginEnabled(): bool
    {
        $plugins = Plugin::query()->orderBy('load_order')->get();
        foreach ($plugins as $plugin) {
            if ($plugin->isTheme() && $plugin->isEnabled()) {
                return true;
            }
        }

        return false;
    }

    /** @return string[] */
    public function getPluginLanguages(): array
    {
        $languages = [];

        $plugins = Plugin::query()->orderBy('load_order')->get();
        foreach ($plugins as $plugin) {
            if (!$plugin->isEnabled() || !$plugin->isLanguage()) {
                continue;
            }

            $languages = array_merge($languages, collect(File::directories(plugin_path($plugin->id, 'lang')))->map(fn ($path) => basename($path))->toArray());
        }

        return array_unique($languages);
    }

    public function isDevModeActive(): bool
    {
        return config('panel.plugin.dev_mode', false);
    }
}
