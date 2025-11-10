<?php

namespace App\Console\Commands\Plugin;

use App\Facades\Plugins;
use Exception;
use Illuminate\Console\Command;

class ComposerPluginsCommand extends Command
{
    protected $signature = 'p:plugin:composer';

    protected $description = 'Makes sure the needed composer packages for all installed plugins are available.';

    public function handle(): void
    {
        try {
            Plugins::manageComposerPackages();
        } catch (Exception $exception) {
            report($exception);

            $this->error($exception->getMessage());
        }
    }
}
