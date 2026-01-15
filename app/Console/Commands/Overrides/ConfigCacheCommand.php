<?php

namespace App\Console\Commands\Overrides;

use Illuminate\Foundation\Console\ConfigCacheCommand as BaseConfigCacheCommand;

class ConfigCacheCommand extends BaseConfigCacheCommand
{
    /**
     * Prevent config from being cached
     */
    public function handle()
    {
        $this->components->warn('Configuration caching has been disabled.');

        $this->line('  Reason: This application uses dynamic plugins. Caching config');
        $this->line('  prevents /plugins/config/*.php files from being loaded correctly.');
    }
}
