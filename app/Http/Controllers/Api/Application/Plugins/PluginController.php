<?php

namespace App\Http\Controllers\Api\Application\Plugins;

use App\Http\Controllers\Api\Application\ApplicationApiController;
use App\Http\Requests\Api\Application\Plugins\GetPluginsRequest;
use App\Http\Requests\Api\Application\Plugins\ImportPluginRequest;
use App\Http\Requests\Api\Application\Plugins\PluginWriteRequest;
use App\Models\Plugin;
use App\Services\Helpers\PluginService;
use App\Transformers\Api\Application\PluginTransformer;
use Illuminate\Http\Response;

class PluginController extends ApplicationApiController
{
    public function __construct(private PluginService $pluginService)
    {
        parent::__construct();
    }

    /**
     * List all plugins
     *
     * Returns all plugins without pagination
     *
     * @return array<mixed>
     */
    public function index(GetPluginsRequest $request): array
    {
        return $this->fractal->collection(Plugin::all())
            ->transformWith($this->getTransformer(PluginTransformer::class))
            ->toArray();
    }

    /**
     * Install a plugin
     *
     * Runs migrations, seeders, and enables the plugin
     *
     * @return array<mixed>
     */
    public function install(PluginWriteRequest $request, Plugin $plugin): array
    {
        $this->pluginService->installPlugin($plugin);

        return $this->fractal->item($plugin->fresh())
            ->transformWith($this->getTransformer(PluginTransformer::class))
            ->toArray();
    }

    /**
     * Enable a plugin
     *
     * @return array<mixed>
     */
    public function enable(PluginWriteRequest $request, Plugin $plugin): array
    {
        $this->pluginService->enablePlugin($plugin);

        return $this->fractal->item($plugin->fresh())
            ->transformWith($this->getTransformer(PluginTransformer::class))
            ->toArray();
    }

    /**
     * Disable a plugin
     *
     * @return array<mixed>
     */
    public function disable(PluginWriteRequest $request, Plugin $plugin): array
    {
        $this->pluginService->disablePlugin($plugin);

        return $this->fractal->item($plugin->fresh())
            ->transformWith($this->getTransformer(PluginTransformer::class))
            ->toArray();
    }

    /**
     * Uninstall a plugin
     *
     * Rolls back migrations and removes the plugin files
     */
    public function uninstall(PluginWriteRequest $request, Plugin $plugin): Response
    {
        $this->pluginService->uninstallPlugin($plugin, deleteFiles: true);

        return $this->returnNoContent();
    }

    /**
     * Update a plugin
     *
     * Downloads and installs the latest version
     *
     * @return array<mixed>
     */
    public function update(PluginWriteRequest $request, Plugin $plugin): array
    {
        $this->pluginService->updatePlugin($plugin);

        return $this->fractal->item($plugin->fresh())
            ->transformWith($this->getTransformer(PluginTransformer::class))
            ->toArray();
    }

    /**
     * Import a plugin from URL
     *
     * Downloads and extracts a plugin from a remote URL
     *
     * @return array<mixed>
     */
    public function import(ImportPluginRequest $request): array
    {
        $this->pluginService->downloadPluginFromUrl($request->input('url'));

        // The plugin model needs to be refreshed to get the new data
        Plugin::clearBootedModels();

        return $this->fractal->collection(Plugin::all())
            ->transformWith($this->getTransformer(PluginTransformer::class))
            ->toArray();
    }
}
