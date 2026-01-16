<?php

namespace App\Services\Backups;

use App\Extensions\BackupAdapter\BackupAdapterService;
use App\Models\Backup;
use App\Models\User;
use Exception;

class DownloadLinkService
{
    public function __construct(private readonly BackupAdapterService $backupService) {}

    /**
     * Returns the URL that allows for a backup to be downloaded by an individual
     * user, or by the daemon control software.
     *
     * @throws Exception
     */
    public function handle(Backup $backup, User $user): string
    {
        $schema = $this->backupService->get($backup->backupHost->schema ?? config('backups.default'));
        if (!$schema) {
            throw new Exception('Backup uses unknown backup adapter.');
        }

        return $schema->getDownloadLink($backup, $user);
    }
}
