<?php

namespace App\Console\Commands\Environment;

use Illuminate\Console\Command;
use App\Traits\Commands\EnvironmentWriterTrait;
use Illuminate\Support\Facades\Artisan;

class AppSettingsCommand extends Command
{
    use EnvironmentWriterTrait;

    protected $description = 'Configure basic environment settings for the Panel.';

    protected $signature = 'p:environment:setup
                            {--url= : The URL that this Panel is running on.}';

    protected array $variables = [];

    public function handle(): void
    {
        $path = base_path('.env');
        if (!file_exists($path)) {
            $this->comment('Copying example .env file');
            copy($path . '.example', $path);
        }

        if (!config('app.key')) {
            $this->comment('Generating app key');
            Artisan::call('key:generate');
        }

        $this->variables['APP_TIMEZONE'] = 'UTC';

        $this->variables['APP_URL'] = $this->option('url') ?? $this->ask(
            'Application URL',
            config('app.url', 'https://example.com')
        );

        // Make sure session cookies are set as "secure" when using HTTPS
        if (str_starts_with($this->variables['APP_URL'], 'https://')) {
            $this->variables['SESSION_SECURE_COOKIE'] = 'true';
        }

        $this->comment('Writing variables to .env file');
        $this->writeToEnvironment($this->variables);

        $this->info("Setup complete. Vist {$this->variables['APP_URL']}/installer to complete the installation");
    }
}
