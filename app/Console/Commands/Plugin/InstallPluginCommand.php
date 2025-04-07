<?php

namespace App\Console\Commands\Plugin;

use App\Models\Plugin;
use App\Services\Helpers\PluginService;
use Illuminate\Console\Command;

class InstallPluginCommand extends Command
{
    protected $signature = 'p:plugin:install {id}';

    protected $description = 'Installs a plugin';

    public function __construct(private PluginService $service)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        /** @var ?Plugin $plugin */
        $plugin = Plugin::where('id', $this->argument('id'))->first();

        if (!$plugin) {
            $this->error('Plugin does not exist!');

            return;
        }

        if ($plugin->isInstalled()) {
            $this->error('Plugin is already installed!');

            return;
        }

        $this->service->installPlugin($plugin);

        $this->info('Plugin installed and enabled.');
    }
}
