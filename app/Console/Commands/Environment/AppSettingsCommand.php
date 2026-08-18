<?php

namespace App\Console\Commands\Environment;

use App\Services\Environment\InstallationHealthService;
use App\Traits\Commands\DisplaysEnvironmentChecks;
use Illuminate\Console\Command;

class AppSettingsCommand extends Command
{
    use DisplaysEnvironmentChecks;

    protected $description = 'Configure basic environment settings for the Panel.';

    protected $signature = 'p:environment:setup';

    public function handle(InstallationHealthService $health): int
    {
        $results = $health->systemRequirements();
        $this->displayEnvironmentChecks($results);

        if ($health->hasFailures($results)) {
            $this->error(trans('commands.environment_check.preflight_failed'));

            return self::FAILURE;
        }

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

        return self::SUCCESS;
    }
}
