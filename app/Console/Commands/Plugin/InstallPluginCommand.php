<?php

namespace App\Console\Commands\Plugin;

use App\Enums\PluginStatus;
use App\Models\Plugin;
use App\Services\Helpers\PluginService;
use Exception;
use Illuminate\Console\Command;

class InstallPluginCommand extends Command
{
    protected $signature = 'p:plugin:install {id?}';

    protected $description = 'Installs a plugin';

    public function handle(PluginService $pluginService): void
    {
        $id = $this->argument('id') ?? $this->choice('Plugin', Plugin::pluck('name', 'id')->toArray());

        $plugin = Plugin::find($id);

        if (!$plugin) {
            $this->error('Plugin does not exist!');

            return;
        }

        if ($plugin->status !== PluginStatus::NotInstalled) {
            $this->error('Plugin is already installed!');

            return;
        }

        try {
            $pluginService->installPlugin($plugin);

            $this->info('Plugin installed and enabled.');
        } catch (Exception $exception) {
            $this->error('Could not install plugin: ' . $exception->getMessage());
        }
    }
}
