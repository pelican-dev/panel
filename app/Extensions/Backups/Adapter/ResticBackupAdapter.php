<?php

namespace App\Extensions\Backups\Adapter;

use App\Extensions\Backups\BackupAdapter;
use App\Models\Backup;
use App\Models\Server;
use Illuminate\Http\JsonResponse;

readonly class ResticBackupAdapter implements BackupAdapter
{
    /**
     * @param  array<string, string>  $resticConfig
     * @param  array<string, string>  $s3Config
     */
    public function __construct(
        private array $resticConfig,
        private array $s3Config,
    ) {}

    /**
     * Provides Wings with the Restic info that's been configured in the panel.
     */
    public function provideUploadInfo(int $backupSize, ?Backup $model, ?Server $server): JsonResponse
    {
        $useS3 = (bool) $this->resticConfig['use_s3'];

        $resticInfo = [
            'use_s3' => $useS3,
            'repository' => $this->resticConfig['repository'],
            'password' => $this->resticConfig['password'],
            'retry_lock_seconds' => (int) $this->resticConfig['retry_lock_seconds'],
        ];

        if ($useS3) {
            $resticInfo['s3'] = [
                'region' => $this->s3Config['region'],
                'access_key_id' => $this->s3Config['key'],
                'access_key' => $this->s3Config['secret'],
                'bucket' => $this->s3Config['bucket'],
                'endpoint' => $this->s3Config['endpoint'],
            ];
        }

        return new JsonResponse($resticInfo);
    }
}
