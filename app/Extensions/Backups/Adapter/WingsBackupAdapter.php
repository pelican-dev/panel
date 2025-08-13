<?php

namespace App\Extensions\Backups\Adapter;

use App\Enums\HttpStatusCode;
use App\Extensions\Backups\BackupAdapter;
use App\Models\Backup;
use App\Models\Server;
use Illuminate\Http\JsonResponse;

class WingsBackupAdapter implements BackupAdapter
{
    public function provideUploadInfo(int $backupSize, Backup $model, Server $server): JsonResponse
    {
        return response()->json([
            'error' => 'WingsBackupAdapter does not support provideUploadInfo().',
        ], HttpStatusCode::BadRequest->value);
    }
}
