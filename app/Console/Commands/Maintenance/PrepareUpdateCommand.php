<?php

namespace App\Console\Commands\Maintenance;

use App\Services\Environment\InstallationHealthService;
use App\Services\Maintenance\UpdateCompatibilityService;
use App\Services\Maintenance\UpdateSnapshotService;
use App\Traits\Commands\DisplaysEnvironmentChecks;
use Illuminate\Console\Command;
use Throwable;

class PrepareUpdateCommand extends Command
{
    use DisplaysEnvironmentChecks;

    protected $description = 'Capture the current Panel state and verify the target release before an update.';

    protected $signature = 'p:maintenance:prepare-update
        {--source= : Extracted target release directory containing composer.json and composer.lock.}
        {--target-version= : Target Panel version recorded in the snapshot metadata.}
        {--skip-queue : Skip the active queue-worker probe.}
        {--queue-timeout=10 : Seconds to wait for the queue probe.}';

    public function handle(
        InstallationHealthService $health,
        UpdateCompatibilityService $compatibility,
        UpdateSnapshotService $snapshots,
    ): int {
        $source = $this->option('source');
        if (!is_string($source) || $source === '') {
            $this->error(trans('commands.update.source_required'));

            return self::FAILURE;
        }

        $results = $health->completeInstallation(
            includeQueue: !$this->option('skip-queue'),
            queueTimeoutSeconds: max(0, (int) $this->option('queue-timeout')),
        );
        $this->displayEnvironmentChecks($results);

        if ($health->hasFailures($results)) {
            $this->error(trans('commands.update.preparation_failed'));

            return self::FAILURE;
        }

        $compatibilityResult = $compatibility->check($source);
        $this->displayEnvironmentChecks([$compatibilityResult]);

        if ($compatibilityResult->failed()) {
            $this->error(trans('commands.update.preparation_failed'));

            return self::FAILURE;
        }

        try {
            $snapshot = $snapshots->capture($this->option('target-version'));
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info(trans('commands.update.snapshot_created', ['path' => $snapshot->path]));
        $this->line(trans('commands.update.database_guidance', ['guidance' => $snapshot->databaseGuidance]));
        $this->info(trans('commands.update.ready'));

        return self::SUCCESS;
    }
}
