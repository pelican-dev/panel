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
 * @property string $namespace
 * @property string $class
 * @property PluginStatus $status
 * @property string|null $status_message
 * @property string|null $panels
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
            'panels' => 'string',
            'panel_version' => 'string',
            'category' => 'string',
        ];
    }

    public function getRows(): array
    {
        $fileSystem = app(Filesystem::class); // @phpstan-ignore-line

        $plugins = [];

        $directories = $fileSystem->directories(base_path('plugins/'));
        foreach ($directories as $directory) {
            $plugin = $fileSystem->basename($directory);

            $path = plugin_path($plugin, 'plugin.json');
            if (!file_exists($path)) {
                continue;
            }

            $plugins[] = $fileSystem->json($path, JSON_THROW_ON_ERROR);
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

    public function shouldLoad(string $panelId): bool
    {
        return !$this->isDisabled() && ($this->panels === null || in_array($panelId, explode(',', $this->panels)));
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

    public function hasSettings(): bool
    {
        $class = $this->fullClass();
        if (class_exists($class) && method_exists($class, 'get')) {
            $pluginObject = ($class)::get();

            return method_exists($pluginObject, 'getSettingsForm') && method_exists($pluginObject, 'saveSettings');
        }

        return false;
    }

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

    public function getProviders(): array
    {
        $class = $this->fullClass();
        if (class_exists($class) && method_exists($class, 'getProviders')) {
            return ($class)::getProviders();
        }

        return [];
    }

    public function getCommands(): array
    {
        $class = $this->fullClass();
        if (class_exists($class) && method_exists($class, 'getCommands')) {
            return ($class)::getCommands();
        }

        return [];
    }
}
