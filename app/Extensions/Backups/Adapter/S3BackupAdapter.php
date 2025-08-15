<?php

namespace App\Extensions\Backups\Adapter;

use App\Extensions\Backups\BackupAdapter;
use App\Models\Backup;
use App\Models\Server;
use Aws\S3\S3ClientInterface;
use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Throwable;

class S3BackupAdapter implements BackupAdapter
{
    public const DEFAULT_MAX_PART_SIZE = 5 * 1024 * 1024 * 1024;

    public function __construct(
        private readonly S3ClientInterface $client,
        private readonly string $bucket,
    ) {}

    public function getClient(): S3ClientInterface
    {
        return $this->client;
    }

    public function getBucket(): string
    {
        return $this->bucket;
    }

    /**
     * Returns the required presigned urls to upload a backup to S3 cloud storage.
     *
     * @throws Exception
     * @throws Throwable
     * @throws ModelNotFoundException
     */
    public function provideUploadInfo(int $backupSize, ?Backup $model, ?Server $server): JsonResponse
    {
        if ($model == null || $server == null) {
            return new JsonResponse(['error' => 'A backup id must be passed to provideUploadInfo for s3.'], Response::HTTP_BAD_REQUEST);
        }

        // Prevent backups that have already been completed from trying to
        // be uploaded again.
        if (!is_null($model->completed_at)) {
            throw new ConflictHttpException('This backup is already in a completed state.');
        }

        // The path where backup will be uploaded to
        $path = sprintf('%s/%s.tar.gz', $server->uuid, $model->uuid);

        // Get the S3 client
        $client = $this->getClient();
        $expires = CarbonImmutable::now()->addMinutes(config('backups.presigned_url_lifespan', 60));

        // Params for generating the presigned urls
        $params = [
            'Bucket' => $this->getBucket(),
            'Key' => $path,
            'ContentType' => 'application/x-gzip',
        ];

        $storageClass = config('backups.disks.s3.storage_class');
        if (!is_null($storageClass)) {
            $params['StorageClass'] = $storageClass;
        }

        // Execute the CreateMultipartUpload request
        $result = $client->execute($client->getCommand('CreateMultipartUpload', $params));

        // Get the UploadId from the CreateMultipartUpload request, this is needed to create
        // the other presigned urls.
        $params['UploadId'] = $result->get('UploadId');

        // Retrieve configured part size
        $maxPartSize = $this->getConfiguredMaxPartSize();

        // Create as many UploadPart presigned urls as needed
        $parts = [];
        for ($i = 0; $i < ($backupSize / $maxPartSize); $i++) {
            $parts[] = $client->createPresignedRequest(
                $client->getCommand('UploadPart', array_merge($params, ['PartNumber' => $i + 1])),
                $expires
            )->getUri()->__toString();
        }

        // Set the upload_id on the backup in the database.
        $model->update(['upload_id' => $params['UploadId']]);

        return new JsonResponse([
            'parts' => $parts,
            'part_size' => $maxPartSize,
        ]);
    }

    /**
     * Get the configured maximum size of a single part in the multipart upload.
     *
     * The function tries to retrieve a configured value from the configuration.
     * If no value is specified, a fallback value will be used.
     *
     * Note if the received config cannot be converted to int (0), is zero or is negative,
     * the fallback value will be used too.
     *
     * The fallback value is {@see self::DEFAULT_MAX_PART_SIZE}.
     */
    private function getConfiguredMaxPartSize(): int
    {
        $maxPartSize = (int) config('backups.max_part_size', self::DEFAULT_MAX_PART_SIZE);
        if ($maxPartSize <= 0) {
            $maxPartSize = self::DEFAULT_MAX_PART_SIZE;
        }

        return $maxPartSize;
    }
}
