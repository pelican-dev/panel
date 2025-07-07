<?php

namespace App\Models;

use App\Contracts\Plugins\HasPluginSettings;
use App\Enums\PluginStatus;
use Exception;
use Filament\Forms\Components\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Sushi\Sushi;

/**
 * @property string $id
 * @property string $name
 * @property string $author
 * @property string $version
 * @property string|null $description
 * @property string $category
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
     *     category: ?string,
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
                'category' => Arr::get($data, 'category', null),
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
        }

        return $plugins;
    }

    protected function sushiShouldCache(): bool
    {
        return false;
    }

    protected function casts(): array
    {
        return [
            'status' => PluginStatus::class,
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
        return !$this->isDisabled() && $this->isInstalled() && !$this->isIncompatible() && ($this->panels === null || in_array($panelId, explode(',', $this->panels)));
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

        return $this->panel_version === null || $panelVersion === 'canary' || version_compare($this->panel_version, $panelVersion, $this->isPanelVersionStrict() ? '=' : '>=');
    }

    public function isPanelVersionStrict(): bool
    {
        if ($this->panel_version === null) {
            return false;
        }

        return !str($this->panel_version)->startsWith('^');
    }

    public function isUpdateAvailable(): bool
    {
        if ($this->update_url === null) {
            return false;
        }

        $panelVersion = config('app.version', 'canary');

        if ($panelVersion === 'canary') {
            return false;
        }

        return cache()->remember("plugins.$this->id.update", now()->addHour(), function () use ($panelVersion) {
            try {
                /** @var array<string, array{version: string, download_url: string}> */
                $updateData = json_decode(file_get_contents($this->update_url), true, 512, JSON_THROW_ON_ERROR);

                if (array_key_exists('*', $updateData)) {
                    return version_compare($updateData['*']['version'], $this->version, '>');
                }

                if (array_key_exists($panelVersion, $updateData)) {
                    return version_compare($updateData[$panelVersion]['version'], $this->version, '>');
                }
            } catch (Exception $exception) {
                report($exception);
            }

            return false;
        });
    }

    public function hasSettings(): bool
    {
        $class = $this->fullClass();
        if (class_exists($class) && method_exists($class, 'get')) {
            $pluginObject = ($class)::get();

            return method_exists($pluginObject, 'getSettingsForm') && method_exists($pluginObject, 'saveSettings');
        }

        return false;
    }

    /**
     * @return Component[]
     */
    public function getSettingsForm(): array
    {
        $class = $this->fullClass();
        if (class_exists($class) && method_exists($class, 'get')) {
            $pluginObject = ($class)::get();

            if (method_exists($pluginObject, 'getSettingsForm')) {
                return $pluginObject->getSettingsForm();
            }
        }

        return [];
    }

    /**
     * @param  array<mixed, mixed>  $data
     */
    public function saveSettings(array $data): void
    {
        $class = $this->fullClass();
        if (class_exists($class) && method_exists($class, 'get')) {
            $pluginObject = ($class)::get();

            if (method_exists($pluginObject, 'saveSettings')) {
                $pluginObject->saveSettings($data);
            }
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
