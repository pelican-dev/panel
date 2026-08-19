<?php

namespace App\Console\Commands\Environment;

use App\Services\Environment\InstallationHealthService;
use App\Traits\Commands\DisplaysHealthResults;
use Illuminate\Console\Command;

class AppSettingsCommand extends Command
{
    use DisplaysHealthResults;

    protected $description = 'Configure basic environment settings for the Panel.';

    protected $signature = 'p:environment:setup
                            {--skip-preflight : Skip server requirement checks before setup.}';

    /**
     * Configure the application after enforcing the installer preflight checks.
     */
    public function handle(InstallationHealthService $health): int
    {
        if (!$this->option('skip-preflight')) {
            $results = $health->systemRequirements();
            $this->displayHealthResults($results);

            if ($health->hasFailures($results)) {
                $this->error(trans('commands.environment_check.preflight_failed'));

                return self::FAILURE;
            }
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
