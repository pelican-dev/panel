<?php

namespace App\Extensions\Backups\Adapter;

use App\Extensions\Backups\BackupAdapter;
use Aws\S3\S3ClientInterface;

readonly class S3BackupAdapter implements BackupAdapter
{
    public function __construct(
        private S3ClientInterface $client,
        private string $bucket,
    ) {}

    public function getClient(): S3ClientInterface
    {
        return $this->client;
    }

    public function getBucket(): string
    {
        return $this->bucket;
    }
}
