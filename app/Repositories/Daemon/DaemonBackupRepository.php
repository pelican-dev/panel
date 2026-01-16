<?php

namespace App\Repositories\Daemon;

use App\Models\Backup;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;

class DaemonBackupRepository extends DaemonRepository
{
    protected ?string $schema;

    /**
     * Sets the backup schema for this execution instance.
     */
    public function setBackupSchema(string $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * Tells the remote Daemon to begin generating a backup for the server.
     *
     * @throws ConnectionException
     */
    public function create(Backup $backup): Response
    {
        return $this->getHttpClient()->post("/api/servers/{$this->server->uuid}/backup",
            [
                'adapter' => $this->schema ?? config('backups.default'),
                'uuid' => $backup->uuid,
                'ignore' => implode("\n", $backup->ignored_files),
            ]
        );
    }

    /**
     * Sends a request to daemon to begin restoring a backup for a server.
     *
     * @throws ConnectionException
     */
    public function restore(Backup $backup, ?string $url = null, bool $truncate = false): Response
    {
        return $this->getHttpClient()->post("/api/servers/{$this->server->uuid}/backup/$backup->uuid/restore",
            [
                'adapter' => $backup->disk,
                'truncate_directory' => $truncate,
                'download_url' => $url ?? '',
            ]
        );
    }

    /**
     * Deletes a backup from the daemon.
     *
     * @throws ConnectionException
     */
    public function delete(Backup $backup): Response
    {
        return $this->getHttpClient()->delete("/api/servers/{$this->server->uuid}/backup/$backup->uuid");
    }
}
