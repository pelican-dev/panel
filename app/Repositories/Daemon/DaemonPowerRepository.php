<?php

namespace App\Repositories\Daemon;

use Illuminate\Http\Client\Response;
use Webmozart\Assert\Assert;
use App\Models\Server;
use GuzzleHttp\Exception\TransferException;
use App\Exceptions\Http\Connection\DaemonConnectionException;

class DaemonPowerRepository extends DaemonRepository
{
    /**
     * Sends a power action to the server instance.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function send(string $action): Response
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            return $this->getHttpClient()->post(
                sprintf('/api/servers/%s/power', $this->server->uuid),
                ['action' => $action],
            );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }
}
