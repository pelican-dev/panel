<?php

namespace App\Repositories\Daemon;

use App\Models\Node;
use GuzzleHttp\Exception\TransferException;
use App\Exceptions\Http\Connection\DaemonConnectionException;
use Illuminate\Http\Client\Response;

class DaemonConfigurationRepository extends DaemonRepository
{
    /**
     * Returns system information from the daemon instance.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function getSystemInformation(?int $version = null, int $connectTimeout = 5): array
    {
        try {
            $response = $this
                ->getHttpClient()
                ->connectTimeout($connectTimeout)
                ->get('/api/system' . (!is_null($version) ? '?v=' . $version : ''));
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }

        return $response->json() ?? [];
    }

    /**
     * Updates the configuration information for a daemon. Updates the information for
     * this instance using a passed-in model. This allows us to change plenty of information
     * in the model, and still use the old, pre-update model to actually make the HTTP request.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function update(Node $node): Response
    {
        try {
            return $this->getHttpClient()->post(
                '/api/update',
                $node->getConfiguration(),
            );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }
}
