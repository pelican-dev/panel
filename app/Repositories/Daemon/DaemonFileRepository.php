<?php

namespace App\Repositories\Daemon;

use Carbon\CarbonInterval;
use Webmozart\Assert\Assert;
use App\Models\Server;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\TransferException;
use App\Exceptions\Http\Server\FileSizeTooLargeException;
use App\Exceptions\Http\Connection\DaemonConnectionException;

class DaemonFileRepository extends DaemonRepository
{
    /**
     * Return the contents of a given file.
     *
     * @param int|null $notLargerThan the maximum content length in bytes
     *
     * @throws \GuzzleHttp\Exception\TransferException
     * @throws \App\Exceptions\Http\Server\FileSizeTooLargeException
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function getContent(string $path, int $notLargerThan = null): string
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            $response = $this->getHttpClient()->get(
                sprintf('/api/servers/%s/files/contents', $this->server->uuid),
                ['file' => $path]
            );
        } catch (ClientException|TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }

        $length = $response->header('Content-Length');
        if ($notLargerThan && $length > $notLargerThan) {
            throw new FileSizeTooLargeException();
        }

        return $response;
    }

    /**
     * Save new contents to a given file. This works for both creating and updating
     * a file.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function putContent(string $path, string $content)
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            return $this->getHttpClient()
                ->withQueryParameters(['file' => $path])
                ->withBody($content)
                ->post(sprintf('/api/servers/%s/files/write', $this->server->uuid));
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }

    /**
     * Return a directory listing for a given path.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function getDirectory(string $path): array
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            $response = $this->getHttpClient()->get(
                sprintf('/api/servers/%s/files/list-directory', $this->server->uuid),
                ['directory' => $path]
            );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }

        return $response->json();
    }

    /**
     * Creates a new directory for the server in the given $path.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function createDirectory(string $name, string $path)
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            return $this->getHttpClient()->post(
                sprintf('/api/servers/%s/files/create-directory', $this->server->uuid),
                [
                    'name' => $name,
                    'path' => $path,
                ]
            );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }

    /**
     * Renames or moves a file on the remote machine.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function renameFiles(?string $root, array $files)
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            return $this->getHttpClient()->put(
                sprintf('/api/servers/%s/files/rename', $this->server->uuid),
                [
                    'root' => $root ?? '/',
                    'files' => $files,
                ]
            );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }

    /**
     * Copy a given file and give it a unique name.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function copyFile(string $location)
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            return $this->getHttpClient()->post(
                sprintf('/api/servers/%s/files/copy', $this->server->uuid),
                [
                    'location' => $location,
                ]
            );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }

    /**
     * Delete a file or folder for the server.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function deleteFiles(?string $root, array $files)
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            return $this->getHttpClient()->post(
                sprintf('/api/servers/%s/files/delete', $this->server->uuid),
                [
                    'root' => $root ?? '/',
                    'files' => $files,
                ]
            );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }

    /**
     * Compress the given files or folders in the given root.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function compressFiles(?string $root, array $files): array
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            $response = $this->getHttpClient()
                // Wait for up to 15 minutes for the archive to be completed when calling this endpoint
                // since it will likely take quite awhile for large directories.
                ->timeout(60 * 15)
                ->post(
                    sprintf('/api/servers/%s/files/compress', $this->server->uuid),
                    [
                        'root' => $root ?? '/',
                        'files' => $files,
                    ]
                );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }

        return $response->json();
    }

    /**
     * Decompresses a given archive file.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function decompressFile(?string $root, string $file)
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            return $this->getHttpClient()
                // Wait for up to 15 minutes for the decompress to be completed when calling this endpoint
                // since it will likely take quite awhile for large directories.
                ->timeout((int) CarbonInterval::minutes(15)->totalSeconds)
                ->post(
                    sprintf('/api/servers/%s/files/decompress', $this->server->uuid),
                    [
                        'root' => $root ?? '/',
                        'file' => $file,
                    ]
                );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }

    /**
     * Chmods the given files.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function chmodFiles(?string $root, array $files)
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            return $this->getHttpClient()->post(
                sprintf('/api/servers/%s/files/chmod', $this->server->uuid),
                [
                    'root' => $root ?? '/',
                    'files' => $files,
                ]
            );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }

    /**
     * Pulls a file from the given URL and saves it to the disk.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function pull(string $url, ?string $directory, array $params = [])
    {
        Assert::isInstanceOf($this->server, Server::class);

        $attributes = [
            'url' => $url,
            'root' => $directory ?? '/',
            'file_name' => $params['filename'] ?? null,
            'use_header' => $params['use_header'] ?? null,
            'foreground' => $params['foreground'] ?? null,
        ];

        try {
            return $this->getHttpClient()->post(
                sprintf('/api/servers/%s/files/pull', $this->server->uuid),
                array_filter($attributes, fn ($value) => !is_null($value))
            );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }
}
