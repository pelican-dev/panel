<?php

namespace App\Models;

use App\Enums\PluginStatus;
use Illuminate\Database\Eloquent\Model as IlluminateModel;
use Illuminate\Filesystem\Filesystem;
use Sushi\Sushi;

/**
 * @property string $id
 * @property string $name
 * @property string $author
 * @property string $version
 * @property string|null $description
 * @property string|null $url
 * @property string $class
 * @property string $namespace
 * @property PluginStatus $status
 * @property string|null $status_message
 * @property string $panel
 * @property string|null $panel_version
 * @property string $category
 */
class Plugin extends IlluminateModel
{
    use Sushi;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

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
            'panel' => 'string',
            'panel_version' => 'string',
            'category' => 'string',
        ];
    }

    public function getRows(): array
    {
        $fileSystem = app(Filesystem::class);

        $plugins = [];

        $directories = $fileSystem->directories(base_path('plugins/'));
        foreach ($directories as $directory) {
            $plugin = $fileSystem->basename($directory);
            $plugins[] = $fileSystem->json(plugin_path($plugin, 'plugin.json'), JSON_THROW_ON_ERROR);
        }

        return $plugins;
    }

    protected function sushiShouldCache()
    {
        return false;
    }

    protected function casts(): array
    {
        return [
            'status' => PluginStatus::class,
        ];
    }

    public function shouldLoad(string $panelId): bool
    {
        return !$this->isDisabled() && ($this->panel === 'both' || $this->panel === $panelId);
    }

    public function isDisabled(): bool
    {
        return $this->status === PluginStatus::Disabled;
    }

    public function hasErrored(): bool
    {
        return $this->status === PluginStatus::Errored;
    }

    public function isCompatible(): bool
    {
        if ($this->panel_version === null) {
            return true;
        }

        if (config('app.version') === 'canary') {
            return false;
        }

        return $this->panel_version === config('app.version');
    }

    public function getFullClass(): string
    {
        return '\\' . $this->namespace . '\\' . $this->class;
    }

    public function hasSettings(): bool
    {
        $class = $this->getFullClass();
        if (class_exists($class) && method_exists($class, 'get')) {
            $pluginObject = ($class)::get();

            return method_exists($pluginObject, 'getSettingsForm') && method_exists($pluginObject, 'saveSettings');
        }

        return false;
    }

    public function getSettingsForm(): array
    {
        $class = $this->getFullClass();
        if (class_exists($class) && method_exists($class, 'get')) {
            $pluginObject = ($class)::get();

            if (method_exists($pluginObject, 'getSettingsForm')) {
                return $pluginObject->getSettingsForm();
            }
        }

        return [];
    }

    public function saveSettings(array $data): void
    {
        $class = $this->getFullClass();
        if (class_exists($class) && method_exists($class, 'get')) {
            $pluginObject = ($class)::get();

            if (method_exists($pluginObject, 'saveSettings')) {
                $pluginObject->saveSettings($data);
            }
        }
    }
}
