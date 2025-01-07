<?php

namespace App\Repositories\Daemon;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;

class DaemonPowerRepository extends DaemonRepository
{
    /**
     * Sends a power action to the server instance.
     *
     * @throws ConnectionException
     */
    public function send(string $action): Response
    {
        return $this->getHttpClient()->post("/api/servers/{$this->server->uuid}/power",
            ['action' => $action],
        );
    }
}
