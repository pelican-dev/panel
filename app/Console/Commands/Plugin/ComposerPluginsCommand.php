<?php

namespace App\Console\Commands\Plugin;

use App\Facades\Plugins;
use App\Models\Plugin;
use App\Traits\Commands\RequiresDatabaseMigrations;
use Exception;
use Illuminate\Console\Command;

class ComposerPluginsCommand extends Command
{
    use RequiresDatabaseMigrations;

    protected $signature = 'p:plugin:composer';

    protected $description = 'Runs "composer require" on all installed plugins.';

    public function handle(): void
    {
        if (!$this->hasCompletedMigrations()) {
            return;
        }

        $plugins = Plugin::all();
        foreach ($plugins as $plugin) {
            if (!$plugin->shouldLoad()) {
                continue;
            }

            try {
                Plugins::requireComposerPackages($plugin);
            } catch (Exception $exception) {
                report($exception);

                $this->error($exception->getMessage());
            }
        }
    }
}
