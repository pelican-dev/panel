<?php

namespace App\Models;

use App\Contracts\Plugins\HasPluginSettings;
use App\Enums\PluginCategory;
use App\Enums\PluginStatus;
use App\Facades\Plugins;
use Exception;
use Filament\Schemas\Components\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use JsonException;
use Sushi\Sushi;

/**
 * @property string $id
 * @property string $name
 * @property string $author
 * @property string $version
 * @property string|null $description
 * @property PluginCategory $category
 * @property string|null $url
 * @property string|null $update_url
 * @property string $namespace
 * @property string $class
 * @property string|null $panels
 * @property string|null $panel_version
 * @property string|null $composer_packages
 * @property PluginStatus $status
 * @property string|null $status_message
 * @property int $load_order
 */
class Plugin extends Model implements HasPluginSettings
{
    use Sushi;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'status',
        'status_message',
        'load_order',
    ];

    /**
     * @return string[]
     */
    public function getSchema(): array
    {
        return [
            'id' => 'string',
            'name' => 'string',
            'author' => 'string',
            'version' => 'string',
            'description' => 'string',
            'category' => 'string',
            'url' => 'string',
            'update_url' => 'string',
            'namespace' => 'string',
            'class' => 'string',
            'panels' => 'string',
            'panel_version' => 'string',
            'composer_packages' => 'string',
            'status' => 'string',
            'status_message' => 'string',
            'load_order' => 'integer',
        ];
    }

    /**
     * @return array<array{
     *     id: string,
     *     name: string,
     *     author: string,
     *     version: string,
     *     description: ?string,
     *     category: string,
     *     url: ?string,
     *     update_url: ?string,
     *     namespace: string,
     *     class: string,
     *     panels: ?string,
     *     panel_version: ?string,
     *     composer_packages: ?string,
     *     status: string,
     *     status_message: ?string,
     *     load_order: int
     * }>
     */
    public function getRows(): array
    {
        $plugins = [];

        $directories = File::directories(base_path('plugins/'));
        foreach ($directories as $directory) {
            $plugin = File::basename($directory);

            $path = plugin_path($plugin, 'plugin.json');
            if (!file_exists($path)) {
                continue;
            }

            try {
                $data = File::json($path, JSON_THROW_ON_ERROR);

                $panels = null;
                if (array_key_exists('panels', $data)) {
                    $panels = $data['panels'];
                    $panels = is_array($panels) ? implode(',', $panels) : $panels;
                }

                $composerPackages = null;
                if (array_key_exists('composer_packages', $data)) {
                    $composerPackages = $data['composer_packages'];
                    $composerPackages = is_array($composerPackages) ? implode(',', $composerPackages) : $composerPackages;
                }

                $data = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'author' => $data['author'],
                    'version' => Arr::get($data, 'version', '1.0.0'),
                    'description' => Arr::get($data, 'description', null),
                    'category' => $data['category'],
                    'url' => Arr::get($data, 'url', null),
                    'update_url' => Arr::get($data, 'update_url', null),
                    'namespace' => $data['namespace'],
                    'class' => $data['class'],
                    'panels' => $panels,
                    'panel_version' => Arr::get($data, 'update_url', null),
                    'composer_packages' => $composerPackages,

                    'status' => Arr::get($data, 'meta.status', PluginStatus::NotInstalled->value),
                    'status_message' => Arr::get($data, 'meta.status_message', null),
                    'load_order' => Arr::integer($data, 'meta.load_order', 0),
                ];

                $plugins[] = $data;
            } catch (Exception $exception) {
                if (Plugins::isDevModeActive()) {
                    throw ($exception);
                }

                report($exception);

                if (!$exception instanceof JsonException) {
                    $plugins[] = [
                        'id' => $data['id'] ?? Str::uuid(),
                        'name' => $data['name'] ?? $plugin,
                        'author' => $data['author'] ?? 'Unknown',
                        'version' => '0.0.0',
                        'description' => 'Plugin.json is invalid!',
                        'category' => PluginCategory::Plugin->value,
                        'url' => null,
                        'update_url' => null,
                        'namespace' => 'Error',
                        'class' => 'Error',
                        'panels' => null,
                        'panel_version' => null,
                        'composer_packages' => null,

                        'status' => PluginStatus::Errored->value,
                        'status_message' => $exception->getMessage(),
                        'load_order' => 0,
                    ];
                }
            }
        }

        return $plugins;
    }

    protected function sushiShouldCache(): bool
    {
        return !Plugins::isDevModeActive();
    }

    protected function casts(): array
    {
        return [
            'status' => PluginStatus::class,
            'category' => PluginCategory::class,
        ];
    }

    public function fullClass(): string
    {
        return '\\' . $this->namespace . '\\' . $this->class;
    }

    public function shouldLoad(): bool
    {
        return !$this->isDisabled() && $this->isInstalled() && !$this->isIncompatible();
    }

    public function shouldLoadPanel(string $panelId): bool
    {
        return !$this->isDisabled() && $this->isInstalled() && !$this->isIncompatible() && (!$this->panels || in_array($panelId, explode(',', $this->panels)));
    }

    public function canEnable(): bool
    {
        return $this->isDisabled() && $this->isInstalled() && $this->isCompatible();
    }

    public function canDisable(): bool
    {
        return $this->isEnabled() && $this->isInstalled() && $this->isCompatible();
    }

    public function isEnabled(): bool
    {
        return $this->status === PluginStatus::Enabled;
    }

    public function isDisabled(): bool
    {
        return $this->status === PluginStatus::Disabled;
    }

    public function isInstalled(): bool
    {
        return $this->status !== PluginStatus::NotInstalled;
    }

    public function hasErrored(): bool
    {
        return $this->status === PluginStatus::Errored;
    }

    public function isIncompatible(): bool
    {
        return $this->status === PluginStatus::Incompatible;
    }

    public function isCompatible(): bool
    {
        $panelVersion = config('app.version', 'canary');

        return !$this->panel_version || $panelVersion === 'canary' || version_compare($this->panel_version, $panelVersion, $this->isPanelVersionStrict() ? '=' : '>=');
    }

    public function isPanelVersionStrict(): bool
    {
        if (!$this->panel_version) {
            return false;
        }

        return !str($this->panel_version)->startsWith('^');
    }

    public function isTheme(): bool
    {
        return $this->category === PluginCategory::Theme;
    }

    public function isLanguage(): bool
    {
        return $this->category === PluginCategory::Language;
    }

    /** @return null|array<string, array{version: string, download_url: string}> */
    private function getUpdateData(): ?array
    {
        if (!$this->update_url) {
            return null;
        }

        return cache()->remember("plugins.$this->id.update", now()->addMinutes(10), function () {
            try {
                return json_decode(file_get_contents($this->update_url), true, 512, JSON_THROW_ON_ERROR);
            } catch (Exception $exception) {
                report($exception);
            }

            return null;
        });
    }

    public function isUpdateAvailable(): bool
    {
        $panelVersion = config('app.version', 'canary');

        if ($panelVersion === 'canary') {
            return false;
        }

        $updateData = $this->getUpdateData();
        if ($updateData) {
            if (array_key_exists($panelVersion, $updateData)) {
                return version_compare($updateData[$panelVersion]['version'], $this->version, '>');
            }

            if (array_key_exists('*', $updateData)) {
                return version_compare($updateData['*']['version'], $this->version, '>');
            }
        }

        return false;
    }

    public function getDownloadUrlForUpdate(): ?string
    {
        $panelVersion = config('app.version', 'canary');

        if ($panelVersion === 'canary') {
            return null;
        }

        $updateData = $this->getUpdateData();
        if ($updateData) {
            if (array_key_exists($panelVersion, $updateData)) {
                return $updateData['panelVersion']['download_url'];
            }

            if (array_key_exists('*', $updateData)) {
                return $updateData['*']['download_url'];
            }
        }

        return null;
    }

    public function hasSettings(): bool
    {
        try {
            $pluginObject = filament($this->id);

            return $pluginObject instanceof HasPluginSettings;
        } catch (Exception) {
            // Plugin is not loaded on the current panel, so no settings
        }

        return false;
    }

    /**
     * @return Component[]
     */
    public function getSettingsForm(): array
    {
        try {
            $pluginObject = filament($this->id);

            if ($pluginObject instanceof HasPluginSettings) {
                return $pluginObject->getSettingsForm();
            }
        } catch (Exception) {
            // Plugin is not loaded on the current panel, so no settings
        }

        return [];
    }

    /**
     * @param  array<mixed, mixed>  $data
     */
    public function saveSettings(array $data): void
    {
        try {
            $pluginObject = filament($this->id);

            if ($pluginObject instanceof HasPluginSettings) {
                $pluginObject->saveSettings($data);
            }
        } catch (Exception) {
            // Plugin is not loaded on the current panel, so no settings
        }
    }

    /**
     * @return string[]
     */
    public function getProviders(): array
    {
        $path = plugin_path($this->id, 'src', 'Providers');

        if (File::missing($path)) {
            return [];
        }

        return array_map(fn ($provider) => $this->namespace . '\\Providers\\' . str($provider->getRelativePathname())->remove('.php', false), File::allFiles($path));
    }

    /**
     * @return string[]
     */
    public function getCommands(): array
    {
        $path = plugin_path($this->id, 'src', 'Console', 'Commands');

        if (File::missing($path)) {
            return [];
        }

        return array_map(fn ($provider) => $this->namespace . '\\Console\\Commands\\' . str($provider->getRelativePathname())->remove('.php', false), File::allFiles($path));
    }
}
