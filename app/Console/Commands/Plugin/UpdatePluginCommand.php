<?php

namespace App\Console\Commands\Plugin;

use App\Facades\Plugins;
use App\Models\Plugin;
use Illuminate\Console\Command;

class UpdatePluginCommand extends Command
{
    protected $signature = 'p:plugin:update {--id=}';

    protected $description = 'Updates a plugin';

    public function handle(): void
    {
        $id = $this->option('id') ?? $this->choice('Plugin', Plugin::pluck('name', 'id')->toArray());

        /** @var ?Plugin $plugin */
        $plugin = Plugin::where('id', $id)->first();

        if (!$plugin) {
            $this->error('Plugin does not exist!');

            return;
        }

        if (!$plugin->isUpdateAvailable()) {
            $this->error("Plugin doesn't need updating!");

            return;
        }

        Plugins::updatePlugin($plugin);

        $this->info('Plugin updated.');
    }
}
