<?php

namespace App\Console\Commands\Plugin;

use App\Facades\Plugins;
use App\Models\Plugin;
use Illuminate\Console\Command;

class DisablePluginCommand extends Command
{
    protected $signature = 'p:plugin:disable {id?}';

    protected $description = 'Disables a plugin';

    public function handle(): void
    {
        $id = $this->argument('id') ?? $this->choice('Plugin', Plugin::pluck('name', 'id')->toArray());

        $plugin = Plugin::find($id);

        if (!$plugin) {
            $this->error('Plugin does not exist!');

            return;
        }

        if (!$plugin->canDisable()) {
            $this->error("Plugin can't be disabled!");

            return;
        }

        Plugins::disablePlugin($plugin);

        $this->info('Plugin disabled.');
    }
}
