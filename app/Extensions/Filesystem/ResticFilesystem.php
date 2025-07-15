<?php

namespace App\Extensions\Filesystem;

use Exception;
use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;

readonly class ResticFilesystem implements FilesystemAdapter
{
    /**
     * @param  array<string, string>  $resticConfig
     * @param  array<string, string>  $s3Config
     */
    public function __construct(
        private array $resticConfig,
        private array $s3Config,
    ) {}

    public function fileExists(string $path): bool
    {
        throw new Exception('Unsupported');
        // TODO-IThundxr: Implement fileExists() method.
    }

    public function directoryExists(string $path): bool
    {
        throw new Exception('Unsupported');
        // TODO-IThundxr: Implement directoryExists() method.
    }

    public function write(string $path, string $contents, Config $config): void
    {
        throw new Exception('Unsupported');
        // TODO-IThundxr: Implement write() method.
    }

    public function writeStream(string $path, $contents, Config $config): void
    {
        throw new Exception('Unsupported');
        // TODO-IThundxr: Implement writeStream() method.
    }

    public function read(string $path): string
    {
        throw new Exception('Unsupported');
        // TODO-IThundxr: Implement read() method.
    }

    public function readStream(string $path)
    {
        throw new Exception('Unsupported');
        // TODO-IThundxr: Implement readStream() method.
    }

    public function delete(string $path): void
    {
        throw new Exception('Unsupported');
        // TODO-IThundxr: Implement delete() method.
    }

    public function deleteDirectory(string $path): void
    {
        throw new Exception('Unsupported');
        // TODO-IThundxr: Implement deleteDirectory() method.
    }

    public function createDirectory(string $path, Config $config): void
    {
        throw new Exception('Unsupported');
        // TODO-IThundxr: Implement createDirectory() method.
    }

    public function setVisibility(string $path, string $visibility): void
    {
        throw new Exception('Unsupported');
        // TODO-IThundxr: Implement setVisibility() method.
    }

    public function visibility(string $path): FileAttributes
    {
        throw new Exception('Unsupported');
        // TODO-IThundxr: Implement visibility() method.
    }

    public function mimeType(string $path): FileAttributes
    {
        throw new Exception('Unsupported');
        // TODO-IThundxr: Implement mimeType() method.
    }

    public function lastModified(string $path): FileAttributes
    {
        throw new Exception('Unsupported');
        // TODO-IThundxr: Implement lastModified() method.
    }

    public function fileSize(string $path): FileAttributes
    {
        throw new Exception('Unsupported');
        // TODO-IThundxr: Implement fileSize() method.
    }

    public function listContents(string $path, bool $deep): iterable
    {
        throw new Exception('Unsupported');
        // TODO-IThundxr: Implement listContents() method.
    }

    public function move(string $source, string $destination, Config $config): void
    {
        throw new Exception('Unsupported');
        // TODO-IThundxr: Implement move() method.
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        throw new Exception('Unsupported');
        // TODO-IThundxr: Implement copy() method.
    }

    public function getResticInfo(): array
    {
        return [
            'use_s3' => $this->resticConfig['use_s3'],
            'repository' => $this->resticConfig['repository'],
            'password' => $this->resticConfig['password'],
            'retry_lock_seconds' => $this->resticConfig['retry_lock_seconds'],
            's3' => [
                'region' => $this->s3Config['region'] ?? null,
                'access_key_id' => $this->s3Config['key'],
                'access_key' => $this->s3Config['secret'],
                'bucket' => $this->s3Config['bucket'],
                'endpoint' => $this->s3Config['endpoint'] ?? null,
            ],
        ];
    }
}
