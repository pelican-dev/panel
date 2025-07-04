<?php

namespace App\Models;

use App\Contracts\Plugins\HasPluginSettings;
use App\Enums\PluginStatus;
use Filament\Forms\Components\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Sushi\Sushi;

/**
 * @property string $id
 * @property string $name
 * @property string $author
 * @property string $version
 * @property string|null $description
 * @property string|null $url
 * @property string $namespace
 * @property string $class
 * @property PluginStatus $status
 * @property string|null $status_message
 * @property string|null $panels
 * @property string|null $panel_version
 * @property string $category
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
            'url' => 'string',
            'namespace' => 'string',
            'class' => 'string',
            'status' => 'string',
            'status_message' => 'string',
            'panels' => 'string',
            'panel_version' => 'string',
            'category' => 'string',
            'load_order' => 'integer',
        ];
    }

    /**
     * @return array<array{
     *     id: string,
     *     name: string,
     *     author: string,
     *     version: string,
     *     description: string,
     *     url: string,
     *     namespace: string,
     *     class: string,
     *     status: string,
     *     status_message: string,
     *     panels: string,
     *     panel_version: string,
     *     category: string,
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

            $plugins[] = File::json($path, JSON_THROW_ON_ERROR);
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

        return $this->panel_version === null || $panelVersion === 'canary' || version_compare($this->panel_version, $panelVersion, $this->isPanelVersionStrict ? '=' : '>=');
    }

    public function isPanelVersionStrict(): bool
    {
        if ($this->panel_version === null) {
            return false;
        }

        return !str($this->panel_version)->startsWith('^');
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
        $class = $this->fullClass();
        if (class_exists($class) && method_exists($class, 'getProviders')) {
            return ($class)::getProviders();
        }

        return [];
    }

    /**
     * @return string[]
     */
    public function getCommands(): array
    {
        $class = $this->fullClass();
        if (class_exists($class) && method_exists($class, 'getCommands')) {
            return ($class)::getCommands();
        }

        return [];
    }
}
