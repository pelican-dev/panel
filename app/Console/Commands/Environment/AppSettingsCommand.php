<?php

namespace App\Console\Commands\Environment;

use App\Traits\EnvironmentWriterTrait;
use Illuminate\Console\Command;

class AppSettingsCommand extends Command
{
    use EnvironmentWriterTrait;

    protected $description = 'Configure basic environment settings for the Panel.';

    protected $signature = 'p:environment:setup
                            {--url= : The URL that this Panel is running on.}';

    public function handle(): int
    {
        $path = base_path('.env');
        if (!file_exists($path)) {
            $this->comment('Copying example .env file');
            copy($path . '.example', $path);
        }

        $appUrl = $this->option('url');
        if (blank($appUrl)) {
            $appUrl = $this->ask('Application URL', config('app.url'));
        }
        if (blank($appUrl)) {
            $this->error('Application URL is required.');

            return 1;
        }
        $this->comment('Writing APP_URL to .env file');
        $this->writeToEnvironment(['APP_URL' => $appUrl]);

        if (!config('app.key')) {
            $this->comment('Generating app key');
            $this->call('key:generate');
        }

        $this->comment('Creating storage link');
        $this->call('storage:link');

        $this->comment('Caching components & icons');
        $this->call('filament:optimize');

        return 0;
    }
}
