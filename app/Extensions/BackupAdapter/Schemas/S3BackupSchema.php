<?php

namespace App\Extensions\BackupAdapter\Schemas;

use App\Http\Controllers\Api\Remote\Backups\BackupRemoteUploadController;
use App\Models\Backup;
use App\Models\User;
use App\Repositories\Daemon\DaemonBackupRepository;
use Aws\S3\S3Client;
use Carbon\CarbonImmutable;
use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\StateCasts\BooleanStateCast;
use Illuminate\Support\Arr;

final class S3BackupSchema extends BackupAdapterSchema
{
    private ?S3Client $client = null;

    public function __construct(private DaemonBackupRepository $repository)
    {
        $this->repository->setBackupSchema($this->getId());
    }

    private function createClient(): void
    {
        if (!$this->client) {
            $config = $this->getConfiguration();
            $config['version'] = 'latest';

            if (!empty($config['key']) && !empty($config['secret'])) {
                $config['credentials'] = Arr::only($config, ['key', 'secret', 'token']);
            }

            $this->client = new S3Client($config);
        }
    }

    public function getId(): string
    {
        return 's3';
    }

    public function createBackup(Backup $backup): void
    {
        $this->repository->setServer($backup->server)->create($backup);
    }

    public function deleteBackup(Backup $backup): void
    {
        $this->createClient();

        $this->client->deleteObject([
            'Bucket' => config('backups.disks.s3.bucket'),
            'Key' => "{$backup->server->uuid}/{$backup->uuid}.tar.gz",
        ]);
    }

    public function getDownloadLink(Backup $backup, User $user): string
    {
        $this->createClient();

        $request = $this->client->createPresignedRequest(
            $this->client->getCommand('GetObject', [
                'Bucket' => config('backups.disks.s3.bucket'),
                'Key' => "{$backup->server->uuid}/{$backup->uuid}.tar.gz",
                'ContentType' => 'application/x-gzip',
            ]),
            CarbonImmutable::now()->addMinutes(5)
        );

        return $request->getUri()->__toString();
    }

    /** @param array<mixed> $configuration */
    public function saveConfiguration(array $configuration): void
    {
        parent::saveConfiguration($configuration);

        $this->client = null;
    }

    /** @return Component[] */
    public function getConfigurationForm(): array
    {
        return [
            TextInput::make('AWS_DEFAULT_REGION')
                ->label(trans('admin/setting.backup.s3.default_region'))
                ->required()
                ->default(config('backups.disks.s3.region')),
            TextInput::make('AWS_ACCESS_KEY_ID')
                ->label(trans('admin/setting.backup.s3.access_key'))
                ->required()
                ->default(config('backups.disks.s3.key')),
            TextInput::make('AWS_SECRET_ACCESS_KEY')
                ->label(trans('admin/setting.backup.s3.secret_key'))
                ->required()
                ->default(config('backups.disks.s3.secret')),
            TextInput::make('AWS_BACKUPS_BUCKET')
                ->label(trans('admin/setting.backup.s3.bucket'))
                ->required()
                ->default(config('backups.disks.s3.bucket')),
            TextInput::make('AWS_ENDPOINT')
                ->label(trans('admin/setting.backup.s3.endpoint'))
                ->required()
                ->default(config('backups.disks.s3.endpoint')),
            Toggle::make('AWS_USE_PATH_STYLE_ENDPOINT')
                ->label(trans('admin/setting.backup.s3.use_path_style_endpoint'))
                ->inline(false)
                ->onIcon('tabler-check')
                ->offIcon('tabler-x')
                ->onColor('success')
                ->offColor('danger')
                ->live()
                ->stateCast(new BooleanStateCast(false))
                ->default(config('backups.disks.s3.use_path_style_endpoint')),
        ];
    }

    /** @return array{parts: string[], part_size: int} */
    public function getUploadParts(Backup $backup, int $size): array
    {
        $expires = CarbonImmutable::now()->addMinutes(config('backups.presigned_url_lifespan', 60));

        // Params for generating the presigned urls
        $params = [
            'Bucket' => config('backups.disks.s3.bucket'),
            'Key' => "{$backup->server->uuid}/{$backup->uuid}.tar.gz",
            'ContentType' => 'application/x-gzip',
        ];

        $storageClass = config('backups.disks.s3.storage_class');
        if (!is_null($storageClass)) {
            $params['StorageClass'] = $storageClass;
        }

        $this->createClient();

        // Execute the CreateMultipartUpload request
        $result = $this->client->execute($this->client->getCommand('CreateMultipartUpload', $params));

        // Get the UploadId from the CreateMultipartUpload request, this is needed to create
        // the other presigned urls.
        $params['UploadId'] = $result->get('UploadId');

        // Retrieve configured part size
        $maxPartSize = config('backups.max_part_size', BackupRemoteUploadController::DEFAULT_MAX_PART_SIZE);
        if ($maxPartSize <= 0) {
            $maxPartSize = BackupRemoteUploadController::DEFAULT_MAX_PART_SIZE;
        }

        // Create as many UploadPart presigned urls as needed
        $parts = [];
        for ($i = 0; $i < ($size / $maxPartSize); $i++) {
            $parts[] = $this->client->createPresignedRequest(
                $this->client->getCommand('UploadPart', array_merge($params, ['PartNumber' => $i + 1])),
                $expires
            )->getUri()->__toString();
        }

        // Set the upload_id on the backup in the database.
        $backup->update(['upload_id' => $params['UploadId']]);

        return [
            'parts' => $parts,
            'part_size' => $maxPartSize,
        ];
    }

    /**
     * Marks a multipart upload in a given S3-compatible instance as failed or successful for the given backup.
     *
     * @param  ?array<array{int, etag: string, part_number: string}>  $parts
     *
     * @throws Exception
     */
    public function completeMultipartUpload(Backup $backup, bool $successful, ?array $parts): void
    {
        // This should never really happen, but if it does don't let us fall victim to Amazon's
        // wildly fun error messaging. Just stop the process right here.
        if (empty($backup->upload_id)) {
            // A failed backup doesn't need to error here, this can happen if the backup encounters
            // an error before we even start the upload. AWS gives you tooling to clear these failed
            // multipart uploads as needed too.
            if (!$successful) {
                return;
            }

            throw new Exception('Cannot complete backup request: no upload_id present on model.');
        }

        $params = [
            'Bucket' => config('backups.disks.s3.bucket'),
            'Key' => "{$backup->server->uuid}/{$backup->uuid}.tar.gz",
            'UploadId' => $backup->upload_id,
        ];

        $this->createClient();

        if (!$successful) {
            $this->client->execute($this->client->getCommand('AbortMultipartUpload', $params));

            return;
        }

        // Otherwise send a CompleteMultipartUpload request.
        $params['MultipartUpload'] = [
            'Parts' => [],
        ];

        if (is_null($parts)) {
            $params['MultipartUpload']['Parts'] = $this->client->execute($this->client->getCommand('ListParts', $params))['Parts'];
        } else {
            foreach ($parts as $part) {
                $params['MultipartUpload']['Parts'][] = [
                    'ETag' => $part['etag'],
                    'PartNumber' => $part['part_number'],
                ];
            }
        }

        $this->client->execute($this->client->getCommand('CompleteMultipartUpload', $params));
    }
}
