<?php

namespace App\Extensions\Backups;

use App\Models\Backup;
use App\Models\Server;
use Exception;
use Illuminate\Http\JsonResponse;

interface BackupAdapter
{
    /**
     * @throws Exception
     */
    public function provideUploadInfo(int $backupSize, ?Backup $model, ?Server $server): JsonResponse;
}
