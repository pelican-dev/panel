<?php

namespace App\Services\Backups;

use App\Extensions\BackupAdapter\BackupAdapterService;
use App\Models\Backup;
use App\Models\User;
use Exception;

class DownloadLinkService
{
    public function __construct(private BackupAdapterService $backupService) {}

    /**
     * Returns the URL that allows for a backup to be downloaded by an individual
     * user, or by the daemon control software.
     */
    public function handle(Backup $backup, User $user): string
    {
        $schema = $this->backupService->get($backup->disk);
        if (!$schema) {
            throw new Exception("Backup uses unknown disk $backup->disk.");
        }

        return $schema->getDownloadLink($backup, $user);
    }
}
