<?php

namespace App\Console\Commands\Plugin;

use App\Enums\PluginStatus;
use App\Models\Plugin;
use App\Services\Helpers\PluginService;
use Exception;
use Illuminate\Console\Command;

class UninstallPluginCommand extends Command
{
    protected $signature = 'p:plugin:uninstall {id?} {--delete : Delete the plugin files}';

    protected $description = 'Uninstalls a plugin';

    public function handle(PluginService $pluginService): void
    {
        $id = $this->argument('id') ?? $this->choice('Plugin', Plugin::pluck('name', 'id')->toArray());

        $plugin = Plugin::find($id);

        if (!$plugin) {
            $this->error('Plugin does not exist!');

            return;
        }

        if ($plugin->status === PluginStatus::NotInstalled) {
            $this->error('Plugin is not installed!');

            return;
        }

        $deleteFiles = $this->option('delete');
        if ($this->input->isInteractive() && !$deleteFiles) {
            $deleteFiles = $this->confirm('Do you also want to delete the plugin files?');
        }

        try {
            $pluginService->uninstallPlugin($plugin, $deleteFiles);

            $this->info('Plugin uninstalled' . ($deleteFiles ? ' and files deleted' : '') . '.');
        } catch (Exception $exception) {
            $this->error('Could not uninstall plugin: ' . $exception->getMessage());
        }
    }
}
