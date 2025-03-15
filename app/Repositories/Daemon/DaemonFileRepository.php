<?php

namespace App\Repositories\Daemon;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Client\Response;
use App\Exceptions\Http\Server\FileSizeTooLargeException;
use Illuminate\Http\Client\ConnectionException;

class DaemonFileRepository extends DaemonRepository
{
    /**
     * Return the contents of a given file.
     *
     * @param  int|null  $notLargerThan  the maximum content length in bytes
     *
     * @throws FileSizeTooLargeException
     * @throws ConnectionException
     * @throws FileNotFoundException
     */
    public function getContent(string $path, ?int $notLargerThan = null): string
    {
        $response = $this->getHttpClient()->get("/api/servers/{$this->server->uuid}/files/contents",
            ['file' => $path]
        );

        $length = $response->header('Content-Length');
        if ($notLargerThan && $length > $notLargerThan) {
            throw new FileSizeTooLargeException();
        }

        if ($response->getStatusCode() === 404) {
            throw new FileNotFoundException();
        }

        return $response;
    }

    /**
     * Save new contents to a given file. This works for both creating and updating
     * a file.
     *
     * @throws ConnectionException
     */
    public function putContent(string $path, string $content): Response
    {
        return $this->getHttpClient()
            ->withQueryParameters(['file' => $path])
            ->withBody($content)
            ->post("/api/servers/{$this->server->uuid}/files/write");
    }

    /**
     * Return a directory listing for a given path.
     *
     * @return array<string, mixed>
     *
     * @throws ConnectionException
     */
    public function getDirectory(string $path): array
    {
        return $this->getHttpClient()->get("/api/servers/{$this->server->uuid}/files/list-directory",
            ['directory' => $path]
        )->json();
    }

    /**
     * Creates a new directory for the server in the given $path.
     *
     * @throws ConnectionException
     */
    public function createDirectory(string $name, string $path): Response
    {
        return $this->getHttpClient()->post("/api/servers/{$this->server->uuid}/files/create-directory",
            [
                'name' => $name,
                'path' => $path,
            ]
        );
    }

    /**
     * Renames or moves a file on the remote machine.
     *
     * @param  array<array{from: string, to: string}>  $files
     *
     * @throws ConnectionException
     */
    public function renameFiles(?string $root, array $files): Response
    {
        return $this->getHttpClient()->put("/api/servers/{$this->server->uuid}/files/rename",
            [
                'root' => $root ?? '/',
                'files' => $files,
            ]
        );
    }

    /**
     * Copy a given file and give it a unique name.
     *
     * @throws ConnectionException
     */
    public function copyFile(string $location): Response
    {
        return $this->getHttpClient()->post("/api/servers/{$this->server->uuid}/files/copy",
            ['location' => $location]
        );
    }

    /**
     * Delete a file or folder for the server.
     *
     * @param  string[]  $files
     *
     * @throws ConnectionException
     */
    public function deleteFiles(?string $root, array $files): Response
    {
        return $this->getHttpClient()->post("/api/servers/{$this->server->uuid}/files/delete",
            [
                'root' => $root ?? '/',
                'files' => $files,
            ]
        );
    }

    /**
     * Compress the given files or folders in the given root.
     *
     * @param  string[]  $files
     * @return array<string, mixed>
     *
     * @throws ConnectionException
     */
    public function compressFiles(?string $root, array $files): array
    {
        return $this->getHttpClient()
            // Wait for up to 15 minutes for the archive to be completed when calling this endpoint
            // since it will likely take quite awhile for large directories.
            ->timeout(60 * 15)
            ->post("/api/servers/{$this->server->uuid}/files/compress",
                [
                    'root' => $root ?? '/',
                    'files' => $files,
                ]
            )->json();
    }

    /**
     * Decompresses a given archive file.
     *
     * @throws ConnectionException
     */
    public function decompressFile(?string $root, string $file): Response
    {
        return $this->getHttpClient()
            // Wait for up to 15 minutes for the archive to be completed when calling this endpoint
            // since it will likely take quite awhile for large directories.
            ->timeout(60 * 15)
            ->post("/api/servers/{$this->server->uuid}/files/decompress",
                [
                    'root' => $root ?? '/',
                    'file' => $file,
                ]
            );
    }

    /**
     * Chmods the given files.
     *
     * @param  array<array{file: string, mode: string}>  $files
     *
     * @throws ConnectionException
     */
    public function chmodFiles(?string $root, array $files): Response
    {
        return $this->getHttpClient()->post("/api/servers/{$this->server->uuid}/files/chmod",
            [
                'root' => $root ?? '/',
                'files' => $files,
            ]
        );
    }

    /**
     * Pulls a file from the given URL and saves it to the disk.
     *
     * @param  array<mixed>  $params
     *
     * @throws ConnectionException
     */
    public function pull(string $url, ?string $directory, array $params = []): Response
    {
        $attributes = [
            'url' => $url,
            'root' => $directory ?? '/',
            'file_name' => $params['filename'] ?? null,
            'use_header' => $params['use_header'] ?? null,
            'foreground' => $params['foreground'] ?? null,
        ];

        return $this->getHttpClient()->post("/api/servers/{$this->server->uuid}/files/pull", array_filter($attributes, fn ($value) => !is_null($value)));
    }

    /**
     * Searches all files in the directory (and its subdirectories) for the given search term.
     *
     * @return array<string, mixed>
     *
     * @throws ConnectionException
     */
    public function search(string $searchTerm, ?string $directory): array
    {
        return $this->getHttpClient()
            // Wait for up to 2 minutes for the search to be completed when calling this endpoint
            // since it will likely take quite awhile for large directories.
            ->timeout(60 * 2)
            ->get("/api/servers/{$this->server->uuid}/files/search",
                [
                    'pattern' => $searchTerm,
                    'directory' => $directory ?? '/',
                ]
            )->json();
    }
}
