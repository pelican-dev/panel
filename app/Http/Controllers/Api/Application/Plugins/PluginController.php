<?php

namespace App\Http\Controllers\Api\Application\Plugins;

use App\Enums\PluginStatus;
use App\Exceptions\PanelException;
use App\Http\Controllers\Api\Application\ApplicationApiController;
use App\Http\Requests\Api\Application\Plugins\DeletePluginRequest;
use App\Http\Requests\Api\Application\Plugins\GetPluginRequest;
use App\Http\Requests\Api\Application\Plugins\UpdatePluginRequest;
use App\Models\Plugin;
use App\Services\Helpers\PluginService;
use App\Transformers\Api\Application\PluginTransformer;
use Exception;
use Spatie\QueryBuilder\QueryBuilder;

class PluginController extends ApplicationApiController
{
    /**
     * PluginController constructor.
     */
    public function __construct(private readonly PluginService $pluginService)
    {
        parent::__construct();
    }

    /**
     * List plugins
     *
     * Return all plugins on the Panel.
     *
     * @return array<array-key, mixed>
     */
    public function index(GetPluginRequest $request): array
    {
        $plugins = QueryBuilder::for(Plugin::class)
            ->allowedFilters(['id', 'name', 'author', 'category'])
            ->allowedSorts(['id', 'name', 'author', 'category'])
            ->paginate($request->query('per_page') ?? 10);

        return $this->fractal->collection($plugins)
            ->transformWith($this->getTransformer(PluginTransformer::class))
            ->toArray();
    }

    /**
     * View plugin
     *
     * Return a single plugin.
     *
     * @return array<array-key, mixed>
     */
    public function view(GetPluginRequest $request, Plugin $plugin): array
    {
        return $this->fractal->item($plugin)
            ->transformWith($this->getTransformer(PluginTransformer::class))
            ->toArray();
    }

    /**
     * Install plugin
     *
     * Installs and enables a plugin.
     *
     * @return array<array-key, mixed>
     *
     * @throws Exception
     */
    public function install(UpdatePluginRequest $request, Plugin $plugin): array
    {
        if ($plugin->status !== PluginStatus::NotInstalled) {
            throw new PanelException('Plugin is already installed');
        }

        $this->pluginService->installPlugin($plugin);

        return $this->fractal->item($plugin)
            ->transformWith($this->getTransformer(PluginTransformer::class))
            ->toArray();
    }

    /**
     * Update plugin
     *
     * Downloads and installs an update for a plugin. Will throw if no update is available.
     *
     * @return array<array-key, mixed>
     *
     * @throws Exception
     */
    public function update(UpdatePluginRequest $request, Plugin $plugin): array
    {
        if (!$plugin->isUpdateAvailable()) {
            throw new PanelException("Plugin doesn't need updating");
        }

        $this->pluginService->updatePlugin($plugin);

        return $this->fractal->item($plugin)
            ->transformWith($this->getTransformer(PluginTransformer::class))
            ->toArray();
    }

    /**
     * Uninstall plugin
     *
     * Uninstalls a plugin. Optionally it will delete the plugin folder too.
     *
     * @return array<array-key, mixed>
     *
     * @throws Exception
     */
    public function uninstall(DeletePluginRequest $request, Plugin $plugin): array
    {
        if ($plugin->status === PluginStatus::NotInstalled) {
            throw new PanelException('Plugin is not installed');
        }

        $this->pluginService->uninstallPlugin($plugin, $request->boolean('delete'));

        return $this->fractal->item($plugin)
            ->transformWith($this->getTransformer(PluginTransformer::class))
            ->toArray();
    }
}
