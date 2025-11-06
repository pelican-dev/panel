<?php

namespace App\Console\Commands\Plugin;

use App\Facades\Plugins;
use App\Models\Plugin;
use Illuminate\Console\Command;

class InstallPluginCommand extends Command
{
    protected $signature = 'p:plugin:install {id?}';

    protected $description = 'Installs a plugin';

    public function handle(): void
    {
        $id = $this->argument('id') ?? $this->choice('Plugin', Plugin::pluck('name', 'id')->toArray());

        $plugin = Plugin::find($id);

        if (!$plugin) {
            $this->error('Plugin does not exist!');

            return;
        }

        if ($plugin->isInstalled()) {
            $this->error('Plugin is already installed!');

            return;
        }

        Plugins::installPlugin($plugin);

        $this->info('Plugin installed and enabled.');
    }
}
