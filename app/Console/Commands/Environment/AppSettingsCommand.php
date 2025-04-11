<?php

namespace App\Console\Commands\Environment;

use Illuminate\Console\Command;

class AppSettingsCommand extends Command
{
    protected $description = 'Configure basic environment settings for the Panel.';

    protected $signature = 'p:environment:setup';

    public function handle(): void
    {
        $path = base_path('.env');
        if (!file_exists($path)) {
            $this->comment('Copying example .env file');
            copy($path . '.example', $path);
        }

        if (!config('app.key')) {
            $this->comment('Generating app key');
            $this->call('key:generate');
        }

        $this->comment('Creating storage link');
        $this->call('storage:link');

        $this->comment('Caching components & icons');
        $this->call('filament:optimize');
    }
}
