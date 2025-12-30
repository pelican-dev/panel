<?php

namespace App\Console\Commands\Plugin;

use App\Models\Plugin;
use App\Services\Helpers\PluginService;
use Exception;
use Illuminate\Console\Command;

class UpdatePluginCommand extends Command
{
    protected $signature = 'p:plugin:update {id?}';

    protected $description = 'Updates a plugin';

    public function handle(PluginService $pluginService): void
    {
        $id = $this->argument('id') ?? $this->choice('Plugin', Plugin::pluck('name', 'id')->toArray());

        $plugin = Plugin::find($id);

        if (!$plugin) {
            $this->error('Plugin does not exist!');

            return;
        }

        if (!$plugin->isUpdateAvailable()) {
            $this->error("Plugin doesn't need updating!");

            return;
        }

        try {
            $pluginService->updatePlugin($plugin);

            $this->info('Plugin updated.');
        } catch (Exception $exception) {
            $this->error('Could not update plugin: ' . $exception->getMessage());
        }
    }
}
