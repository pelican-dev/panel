<?php

namespace App\Services\Helpers;

use App\Enums\PluginStatus;
use App\Exceptions\Service\InvalidFileUploadException;
use App\Models\Plugin;
use Composer\Autoload\ClassLoader;
use Exception;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Console\Application as ConsoleApplication;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Foundation\Application;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use JsonException;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use ZipArchive;

class PluginService
{
    public function __construct(private Application $app) {}

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
                    if ($plugin->status === PluginStatus::Incompatible) {
                        $this->disablePlugin($plugin);
                    }
                }

                // Always autoload src directory to make sure all class names can be resolved (e.g. in migrations)
                $namespace = $plugin->namespace . '\\';
                if (!array_key_exists($namespace, $classLoader->getPrefixesPsr4())) {
                    $classLoader->setPsr4($namespace, plugin_path($plugin->id, 'src/'));

                    $classLoader->addPsr4('Database\Factories\\', plugin_path($plugin->id, 'database/Factories/'));
                    $classLoader->addPsr4('Database\Seeders\\', plugin_path($plugin->id, 'database/Seeders/'));
                }

                // Load config
                $config = plugin_path($plugin->id, 'config', $plugin->id . '.php');
                if (file_exists($config)) {
                    config()->set($plugin->id, require $config);
                }

                // Filter out plugins that should not be loaded (e.g. because they are disabled or not installed yet)
                if (!$plugin->shouldLoad()) {
                    continue;
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
                $this->handlePluginException($plugin, $exception);
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
                if (!$plugin->shouldLoad($panel->getId())) {
                    continue;
                }

                $pluginClass = $plugin->fullClass();

                if (!class_exists($pluginClass)) {
                    throw new Exception('Class "' . $pluginClass . '" not found');
                }

                $panel->plugin(new $pluginClass());

                if ($plugin->status === PluginStatus::Errored) {
                    $this->enablePlugin($plugin);
                }
            } catch (Exception $exception) {
                $this->handlePluginException($plugin, $exception);
            }
        }
    }

    /**
     * @param  null|array<string, string>  $newPackages
     * @param  null|array<string, string>  $oldPackages
     *
     * @throws Exception
     */
    public function manageComposerPackages(?array $newPackages = [], ?array $oldPackages = null): void
    {
        $newPackages ??= [];

        $plugins = Plugin::query()->orderBy('load_order')->get();
        foreach ($plugins as $plugin) {
            if (!$plugin->composer_packages) {
                continue;
            }

            if (!$plugin->shouldLoad()) {
                continue;
            }

            try {
                $pluginPackages = json_decode($plugin->composer_packages, true, 512, JSON_THROW_ON_ERROR);

                $newPackages = array_merge($newPackages, $pluginPackages);
            } catch (Exception $exception) {
                report($exception);
            }
        }

        $oldPackages = collect($oldPackages)
            ->filter(fn ($version, $package) => !array_key_exists($package, $newPackages))
            ->keys()
            ->unique()
            ->toArray();

        if (count($oldPackages) > 0) {
            $result = Process::path(base_path())->timeout(600)->run(['composer', 'remove', ...$oldPackages]);
            if ($result->failed()) {
                throw new Exception('Could not remove old composer packages: ' . $result->errorOutput());
            }
        }

        $newPackages = collect($newPackages)
            ->map(fn ($version, $package) => "$package:$version")
            ->flatten()
            ->unique()
            ->toArray();

        if (count($newPackages) > 0) {
            $result = Process::path(base_path())->timeout(600)->run(['composer', 'require', ...$newPackages]);
            if ($result->failed()) {
                throw new Exception('Could not require new composer packages: ' . $result->errorOutput());
            }
        }
    }

    /** @throws Exception */
    public function runPluginMigrations(Plugin $plugin): void
    {
        $migrations = plugin_path($plugin->id, 'database', 'migrations');
        if (file_exists($migrations)) {
            try {
                $migrator = $this->app->make(Migrator::class);
                $migrator->run($migrations);
            } catch (Exception $exception) {
                throw new Exception("Could not run migrations': " . $exception->getMessage());
            }
        }
    }

    /** @throws Exception */
    public function rollbackPluginMigrations(Plugin $plugin): void
    {
        $migrations = plugin_path($plugin->id, 'database', 'migrations');
        if (file_exists($migrations)) {
            try {
                $migrator = $this->app->make(Migrator::class);
                $migrator->reset($migrations);
            } catch (Exception $exception) {
                throw new Exception("Could not rollback migrations': " . $exception->getMessage());
            }
        }
    }

    /** @throws Exception */
    public function runPluginSeeder(Plugin $plugin): void
    {
        $seeder = $plugin->getSeeder();
        if ($seeder) {
            try {
                $seederObject = $this->app->make($seeder)->setContainer($this->app);

                Model::unguarded(fn () => $seederObject->__invoke());
            } catch (Exception $exception) {
                throw new Exception('Could not run seeder: ' . $exception->getMessage());
            }
        }
    }

    public function buildAssets(bool $throw = false): bool
    {
        try {
            $result = Process::path(base_path())->timeout(300)->run('yarn install');
            if ($result->failed()) {
                throw new Exception('Could not install yarn dependencies: ' . $result->errorOutput());
            }

            $result = Process::path(base_path())->timeout(600)->run('yarn build');
            if ($result->failed()) {
                throw new Exception('Could not build assets: ' . $result->errorOutput());
            }

            return true;
        } catch (Exception $exception) {
            if ($throw || $this->isDevModeActive()) {
                throw ($exception);
            }

            Log::warning($exception->getMessage(), ['exception' => $exception]);
        }

        return false;
    }

    /** @throws Exception */
    public function installPlugin(Plugin $plugin, bool $enable = true): void
    {
        try {
            $this->manageComposerPackages(json_decode($plugin->composer_packages, true, 512));

            if ($enable) {
                $this->enablePlugin($plugin);
            } else {
                if ($plugin->status === PluginStatus::NotInstalled) {
                    $this->disablePlugin($plugin);
                }
            }

            $this->buildAssets($plugin->isTheme());

            $this->runPluginMigrations($plugin);

            $this->runPluginSeeder($plugin);

            foreach (Filament::getPanels() as $panel) {
                $panel->clearCachedComponents();
            }
        } catch (Exception $exception) {
            $this->handlePluginException($plugin, $exception, true);
        }
    }

    /** @throws Exception */
    public function updatePlugin(Plugin $plugin): void
    {
        try {
            $downloadUrl = $plugin->getDownloadUrlForUpdate();
            if (!$downloadUrl) {
                throw new Exception('No download url found.');
            }

            $this->downloadPluginFromUrl($downloadUrl, true);

            $this->installPlugin($plugin, false);

            cache()->forget("plugins.$plugin->id.update");
        } catch (Exception $exception) {
            $this->handlePluginException($plugin, $exception, true);
        }
    }

    /** @throws Exception */
    public function uninstallPlugin(Plugin $plugin, bool $deleteFiles = false): void
    {
        try {
            $pluginPackages = json_decode($plugin->composer_packages, true, 512);

            $this->rollbackPluginMigrations($plugin);

            if ($deleteFiles) {
                $this->deletePlugin($plugin);
            } else {
                $this->setStatus($plugin, PluginStatus::NotInstalled);
            }

            $this->buildAssets();

            $this->manageComposerPackages(oldPackages: $pluginPackages);

            // This throws an error when not called with qualifier
            foreach (\Filament\Facades\Filament::getPanels() as $panel) {
                $panel->clearCachedComponents();
            }
        } catch (Exception $exception) {
            $this->handlePluginException($plugin, $exception, true);
        }
    }

    /** @throws Exception */
    public function downloadPluginFromFile(UploadedFile $file, bool $cleanDownload = false): void
    {
        // Validate file size to prevent zip bombs
        $maxSize = config('panel.plugin.max_import_size');
        if ($file->getSize() > $maxSize) {
            throw new Exception("Zip file too large. ($maxSize  MiB)");
        }

        $zip = new ZipArchive();

        if (!$zip->open($file->getPathname())) {
            throw new Exception('Could not open zip file.');
        }

        // Validate zip contents before extraction
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (Str::contains($filename, '..') || Str::startsWith($filename, '/')) {
                $zip->close();
                throw new Exception('Zip file contains invalid path traversal sequences.');
            }
        }

        $pluginName = str($file->getClientOriginalName())->before('.zip')->toString();

        if ($cleanDownload) {
            File::deleteDirectory(plugin_path($pluginName));
        }

        $extractPath = $zip->locateName($pluginName . '/plugin.json') !== false ? base_path('plugins') : plugin_path($pluginName);

        if (!$zip->extractTo($extractPath)) {
            $zip->close();
            throw new Exception('Could not extract zip file.');
        }

        $zip->close();
    }

    /** @throws Exception */
    public function downloadPluginFromUrl(string $url, bool $cleanDownload = false): void
    {
        $info = pathinfo($url);
        $tmpDir = TemporaryDirectory::make()->deleteWhenDestroyed();
        $tmpPath = $tmpDir->path($info['basename']);

        $content = Http::timeout(60)->connectTimeout(5)->throw()->get($url)->body();

        // Validate file size to prevent zip bombs
        $maxSize = config('panel.plugin.max_import_size');
        if (strlen($content) > $maxSize) {
            throw new InvalidFileUploadException("Zip file too large. ($maxSize  MiB)");
        }

        if (!file_put_contents($tmpPath, $content)) {
            throw new InvalidFileUploadException('Could not write temporary file.');
        }

        $this->downloadPluginFromFile(new UploadedFile($tmpPath, $info['basename'], 'application/zip'), $cleanDownload);
    }

    public function deletePlugin(Plugin $plugin): void
    {
        File::deleteDirectory(plugin_path($plugin->id));
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
        $path = plugin_path($plugin instanceof Plugin ? $plugin->id : $plugin, 'plugin.json');

        if (File::exists($path)) {
            $pluginData = File::json($path, JSON_THROW_ON_ERROR);
            $metaData = array_merge($pluginData['meta'] ?? [], $data);
            $pluginData['meta'] = $metaData;

            File::put($path, json_encode($pluginData, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            $plugin = $plugin instanceof Plugin ? $plugin : Plugin::findOrFail($plugin);
            $plugin->update($metaData);
        }
    }

    private function setStatus(string|Plugin $plugin, PluginStatus $status, ?string $message = null): void
    {
        $this->setMetaData($plugin, [
            'status' => $status,
            'status_message' => $message,
        ]);
    }

    /**
     * @param  array<int, string>  $order
     *
     * @throws JsonException
     */
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
            if ($plugin->isTheme() && $plugin->status === PluginStatus::Enabled) {
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
            if ($plugin->status !== PluginStatus::Enabled || !$plugin->isLanguage()) {
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

    private function handlePluginException(string|Plugin $plugin, Exception $exception, bool $throw = false): void
    {
        $this->setStatus($plugin, PluginStatus::Errored, $exception->getMessage());

        if ($throw || $this->isDevModeActive()) {
            throw ($exception);
        }

        report($exception);
    }
}
