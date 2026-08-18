<?php

namespace App\Console\Commands\Environment;

use App\Services\Environment\InstallationHealthService;
use App\Traits\Commands\DisplaysEnvironmentChecks;
use Illuminate\Console\Command;

class EnvironmentPreflightCommand extends Command
{
    use DisplaysEnvironmentChecks;

    protected $description = 'Verify server requirements before installing or reconfiguring the Panel.';

    protected $signature = 'p:environment:preflight
        {--with-database : Also verify the currently configured database connection.}
        {--with-queue : Also dispatch a real job to verify the configured queue worker.}
        {--queue-timeout=10 : Seconds to wait for the queue probe.}';

    public function handle(InstallationHealthService $health): int
    {
        $results = $health->systemRequirements();

        if ($this->option('with-database')) {
            $driver = (string) config('database.default');
            $results[] = $health->databaseDriverExtension($driver);
            $results[] = $health->database();
        }

        if ($this->option('with-queue')) {
            $results[] = $health->queueWorker($this->queueTimeout());
        }

        $this->displayEnvironmentChecks($results);

        if ($health->hasFailures($results)) {
            $this->error(trans('commands.environment_check.preflight_failed'));

            return self::FAILURE;
        }

        $this->info(trans('commands.environment_check.preflight_passed'));

        return self::SUCCESS;
    }

    private function queueTimeout(): int
    {
        return max(0, (int) $this->option('queue-timeout'));
    }
}
