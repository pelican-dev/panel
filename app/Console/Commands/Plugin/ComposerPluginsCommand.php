<?php

namespace App\Console\Commands\Plugin;

use App\Facades\Plugins;
use App\Models\Plugin;
use Exception;
use Illuminate\Console\Command;

class ComposerPluginsCommand extends Command
{
    protected $signature = 'p:plugin:composer';

    protected $description = 'Runs composer require on all installed plugins.';

    public function handle(): void
    {
        $plugins = Plugin::all();
        foreach ($plugins as $plugin) {
            try {
                Plugins::requireComposerPackages($plugin);
            } catch (Exception $exception) {
                report($exception);

                $this->error($exception->getMessage());
            }
        }
    }
}
