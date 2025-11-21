<?php

namespace App\Console\Commands\Plugin;

use App\Models\Plugin;
use Illuminate\Console\Command;

class ListPluginsCommand extends Command
{
    protected $signature = 'p:plugin:list';

    protected $description = 'List all installed plugins';

    public function handle(): void
    {
        $plugins = Plugin::query()->get(['name', 'author', 'status', 'version', 'panels', 'category']);

        if (count($plugins) < 1) {
            $this->warn('No plugins installed');

            return;
        }

        $this->table(['Name', 'Author', 'Status', 'Version', 'Panels', 'Category'], $plugins->toArray());

        $this->output->newLine();
    }
}
