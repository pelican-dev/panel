<?php

namespace App\Models;

use Sushi\Sushi;

/**
 * @property string name
 * @property string created
 * @property string modified
 * @property string created
 * @property string mode
 * @property string mode_bits
 * @property int size
 * @property bool is_directory
 * @property bool is_file
 * @property bool is_symlink
 * @property string mime
 */
class File extends Model
{
    use Sushi;

    protected $primaryKey = 'name';

    public $incrementing = false;

    protected $keyType = 'string';

    public const ARCHIVE_MIMES = [
        'application/vnd.rar', // .rar
        'application/x-rar-compressed', // .rar (2)
        'application/x-tar', // .tar
        'application/x-br', // .tar.br
        'application/x-bzip2', // .tar.bz2, .bz2
        'application/gzip', // .tar.gz, .gz
        'application/x-gzip',
        'application/x-lzip', // .tar.lz4, .lz4 (not sure if this mime type is correct)
        'application/x-sz', // .tar.sz, .sz (not sure if this mime type is correct)
        'application/x-xz', // .tar.xz, .xz
        'application/x-7z-compressed', // .7z
        'application/zstd', // .tar.zst, .zst
        'application/zip', // .zip
    ];

    protected static Server $server;
    protected static string $directory;

    public static function get(Server $server, string $directory = '/')
    {
        self::$server = $server;
        self::$directory = $directory;

        return self::query();
    }

    public function isArchive(): bool
    {
        return $this->is_file && in_array($this->mime, self::ARCHIVE_MIMES);
    }

    public function getIcon(): string
    {
        if ($this->is_directory) {
            return 'tabler-folder';
        }

        if ($this->isArchive()) {
            return 'tabler-file-zip';
        }

        return $this->is_symlink ? 'tabler-file-symlink' : 'tabler-file';
    }

    public function canEdit(): bool
    {
        if ($this->is_directory || $this->isArchive() || $this->is_symlink) {
            return false;
        }

        return $this->is_file && !in_array($this->mime, ['application/jar', 'application/octet-stream', 'inode/directory']);
    }

    public function server()
    {
        return self::$server;
    }

    public function getRows()
    {
        // TODO: replace hardcoded dummy data with api call
        // return $this->fileRepository->setServer($this->server())->getDirectory(self::$directory ?? '/')

        $directory = self::$directory ?? '/';
        if ($directory === 'versions') {
            return [
                [
                    'name' => '1.21',
                    'created' => '2024-08-09T08:52:23+02:00',
                    'modified' => '2024-08-09T08:52:02+02:00',
                    'mode' => 'drwxr-xr-x',
                    'mode_bits' => '755',
                    'size' => 4096,
                    'is_directory' => true,
                    'is_file' => false,
                    'is_symlink' => false,
                    'mime' => 'inode/directory',
                ],
            ];
        } elseif ($directory === 'versions/1.21') {
            return [
                [
                    'name' => 'paper-1.19.4.jar',
                    'created' => '2024-08-09T08:52:23+02:00',
                    'modified' => '2024-08-09T08:52:02+02:00',
                    'mode' => '-rw-r--r--',
                    'mode_bits' => '644',
                    'size' => 20765365,
                    'is_directory' => false,
                    'is_file' => true,
                    'is_symlink' => false,
                    'mime' => 'application/jar',
                ],

            ];
        } elseif ($directory === 'plugins') {
            return [
                [
                    'name' => '.paper-remapped',
                    'created' => '2024-08-09T08:52:23+02:00',
                    'modified' => '2024-08-09T08:52:08+02:00',
                    'mode' => 'drwxr-xr-x',
                    'mode_bits' => '755',
                    'size' => 4096,
                    'is_directory' => true,
                    'is_file' => false,
                    'is_symlink' => false,
                    'mime' => 'inode/directory',
                ],
            ];
        } elseif ($directory === 'logs') {
            return [
                [
                    'name' => 'latest.log',
                    'created' => '2024-08-09T08:52:33+02:00',
                    'modified' => '2024-08-09T08:52:33+02:00',
                    'mode' => '-rw-r--r--',
                    'mode_bits' => '644',
                    'size' => 566,
                    'is_directory' => false,
                    'is_file' => true,
                    'is_symlink' => false,
                    'mime' => 'text/plain; charset=utf-8',
                ],
                [
                    'name' => '2024-08-09-1.log.gz',
                    'created' => '2024-08-09T08:52:26+02:00',
                    'modified' => '2024-08-09T08:52:26+02:00',
                    'mode' => '-rw-r--r--',
                    'mode_bits' => '644',
                    'size' => 402,
                    'is_directory' => false,
                    'is_file' => true,
                    'is_symlink' => false,
                    'mime' => 'application/gzip',
                ],
            ];
        } else {
            return [
                [
                    'name' => 'server.properties',
                    'created' => '2024-08-09T08:52:33+02:00',
                    'modified' => '2024-08-09T08:52:33+02:00',
                    'mode' => '-rw-r--r--',
                    'mode_bits' => '644',
                    'size' => 1396,
                    'is_directory' => false,
                    'is_file' => true,
                    'is_symlink' => false,
                    'mime' => 'text/plain; charset=utf-8',
                ],
                [
                    'name' => 'server.jar',
                    'created' => '2024-08-09T08:52:23+02:00',
                    'modified' => '2024-08-09T08:51:55+02:00',
                    'mode' => '-rw-r--r--',
                    'mode_bits' => '644',
                    'size' => 49020968,
                    'is_directory' => false,
                    'is_file' => true,
                    'is_symlink' => false,
                    'mime' => 'application/jar',
                ],
                [
                    'name' => 'eula.txt',
                    'created' => '2024-08-09T08:52:23+02:00',
                    'modified' => '2024-08-09T08:52:16+02:00',
                    'mode' => '-rw-r--r--',
                    'mode_bits' => '644',
                    'size' => 159,
                    'is_directory' => false,
                    'is_file' => true,
                    'is_symlink' => false,
                    'mime' => 'text/plain; charset=utf-8',
                ],
                [
                    'name' => '.cache',
                    'created' => '2024-08-09T08:52:23+02:00',
                    'modified' => '2024-08-09T08:52:07+02:00',
                    'mode' => 'drwxr-xr-x',
                    'mode_bits' => '755',
                    'size' => 4096,
                    'is_directory' => true,
                    'is_file' => false,
                    'is_symlink' => false,
                    'mime' => 'inode/directory',
                ],
                [
                    'name' => 'cache',
                    'created' => '2024-08-09T08:52:23+02:00',
                    'modified' => '2024-08-09T08:51:58+02:00',
                    'mode' => 'drwxr-xr-x',
                    'mode_bits' => '755',
                    'size' => 4096,
                    'is_directory' => true,
                    'is_file' => false,
                    'is_symlink' => false,
                    'mime' => 'inode/directory',
                ],
                [
                    'name' => 'libraries',
                    'created' => '2024-08-09T08:52:23+02:00',
                    'modified' => '2024-08-09T08:52:01+02:00',
                    'mode' => 'drwxr-xr-x',
                    'mode_bits' => '755',
                    'size' => 4096,
                    'is_directory' => true,
                    'is_file' => false,
                    'is_symlink' => false,
                    'mime' => 'inode/directory',
                ],
                [
                    'name' => 'logs',
                    'created' => '2024-08-09T08:52:26+02:00',
                    'modified' => '2024-08-09T08:52:26+02:00',
                    'mode' => 'drwxr-xr-x',
                    'mode_bits' => '755',
                    'size' => 4096,
                    'is_directory' => true,
                    'is_file' => false,
                    'is_symlink' => false,
                    'mime' => 'inode/directory',
                ],
                [
                    'name' => 'plugins',
                    'created' => '2024-08-09T08:52:23+02:00',
                    'modified' => '2024-08-09T08:52:07+02:00',
                    'mode' => 'drwxr-xr-x',
                    'mode_bits' => '755',
                    'size' => 4096,
                    'is_directory' => true,
                    'is_file' => false,
                    'is_symlink' => false,
                    'mime' => 'inode/directory',
                ],
                [
                    'name' => 'versions',
                    'created' => '2024-08-09T08:52:23+02:00',
                    'modified' => '2024-08-09T08:52:02+02:00',
                    'mode' => 'drwxr-xr-x',
                    'mode_bits' => '755',
                    'size' => 4096,
                    'is_directory' => true,
                    'is_file' => false,
                    'is_symlink' => false,
                    'mime' => 'inode/directory',
                ],
            ];
        }
    }

    protected function sushiShouldCache()
    {
        return false;
    }
}
