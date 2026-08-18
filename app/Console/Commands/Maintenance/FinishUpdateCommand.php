<?php

namespace App\Console\Commands\Maintenance;

use App\Services\Environment\InstallationHealthService;
use App\Services\Maintenance\UpdateSnapshotService;
use App\Traits\Commands\DisplaysEnvironmentChecks;
use Illuminate\Console\Command;

class FinishUpdateCommand extends Command
{
    use DisplaysEnvironmentChecks;

    protected $description = 'Validate the Panel after an update and show rollback guidance when validation fails.';

    protected $signature = 'p:maintenance:finish-update
        {--snapshot= : Pre-update snapshot directory. The newest snapshot is used by default.}
        {--skip-queue : Skip the active queue-worker probe.}
        {--queue-timeout=10 : Seconds to wait for the queue probe.}';

    public function handle(InstallationHealthService $health, UpdateSnapshotService $snapshots): int
    {
        $results = $health->completeInstallation(
            includeQueue: !$this->option('skip-queue'),
            queueTimeoutSeconds: max(0, (int) $this->option('queue-timeout')),
        );
        $this->displayEnvironmentChecks($results);

        if (!$health->hasFailures($results)) {
            $this->info(trans('commands.update.healthy'));

            return self::SUCCESS;
        }

        $snapshotOption = $this->option('snapshot');
        $snapshot = is_string($snapshotOption) && $snapshotOption !== ''
            ? $snapshots->fromPath($snapshotOption)
            : $snapshots->latest();

        if ($snapshot === null) {
            $this->error(trans('commands.update.snapshot_missing'));

            return self::FAILURE;
        }

        $this->error(trans('commands.update.unhealthy', ['path' => $snapshot->rollbackGuide]));

        return self::FAILURE;
    }
}
