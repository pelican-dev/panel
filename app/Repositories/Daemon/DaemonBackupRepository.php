<?php

namespace App\Repositories\Daemon;

use Webmozart\Assert\Assert;
use App\Models\Backup;
use App\Models\Server;
use GuzzleHttp\Exception\TransferException;
use App\Exceptions\Http\Connection\DaemonConnectionException;

class DaemonBackupRepository extends DaemonRepository
{
    protected ?string $adapter;

    /**
     * Sets the backup adapter for this execution instance.
     */
    public function setBackupAdapter(string $adapter): self
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * Tells the remote Daemon to begin generating a backup for the server.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function backup(Backup $backup)
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            return $this->getHttpClient()->post(
                sprintf('/api/servers/%s/backup', $this->server->uuid),
                [
                    'adapter' => $this->adapter ?? config('backups.default'),
                    'uuid' => $backup->uuid,
                    'ignore' => implode("\n", $backup->ignored_files),
                ]
            );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }

    /**
     * Sends a request to daemon to begin restoring a backup for a server.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function restore(Backup $backup, string $url = null, bool $truncate = false)
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            return $this->getHttpClient()->post(
                sprintf('/api/servers/%s/backup/%s/restore', $this->server->uuid, $backup->uuid),
                [
                    'adapter' => $backup->disk,
                    'truncate_directory' => $truncate,
                    'download_url' => $url ?? '',
                ]
            );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }

    /**
     * Deletes a backup from the daemon.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function delete(Backup $backup)
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            return $this->getHttpClient()->delete(
                sprintf('/api/servers/%s/backup/%s', $this->server->uuid, $backup->uuid)
            );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }
}
