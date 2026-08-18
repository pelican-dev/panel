<?php

namespace App\Console\Commands\Environment;

use App\Services\Environment\InstallationHealthService;
use App\Traits\Commands\DisplaysEnvironmentChecks;
use Illuminate\Console\Command;

class EnvironmentHealthCommand extends Command
{
    use DisplaysEnvironmentChecks;

    protected $description = 'Validate the complete Panel installation after an install or update.';

    protected $signature = 'p:environment:health
        {--skip-queue : Skip the active queue-worker probe.}
        {--queue-timeout=10 : Seconds to wait for the queue probe.}';

    public function handle(InstallationHealthService $health): int
    {
        $results = $health->completeInstallation(
            includeQueue: !$this->option('skip-queue'),
            queueTimeoutSeconds: max(0, (int) $this->option('queue-timeout')),
        );

        $this->displayEnvironmentChecks($results);

        if ($health->hasFailures($results)) {
            $this->error(trans('commands.environment_check.health_failed'));

            return self::FAILURE;
        }

        $this->info(trans('commands.environment_check.health_passed'));

        return self::SUCCESS;
    }
}
