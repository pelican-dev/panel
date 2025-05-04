<?php

namespace App\Repositories\Daemon;

use App\Models\Node;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;

class DaemonConfigurationRepository extends DaemonRepository
{
    /**
     * Returns system information from the daemon instance.
     *
     * @return array<mixed>
     *
     * @throws ConnectionException
     */
    public function getSystemInformation(): array
    {
        return $this->getHttpClient()
            ->connectTimeout(3)
            ->get('/api/system')
            ->throwIf(function ($result) {
                $this->enforceValidNodeToken($result);
                if (!$result->collect()->has(['architecture', 'cpu_count', 'kernel_version', 'os', 'version'])) {
                    throw new ConnectionException($result->effectiveUri()->__toString() . ' is not Pelican Wings !');
                }

                return true;
            })->json();
    }

    /**
     * Updates the configuration information for a daemon. Updates the information for
     * this instance using a passed-in model. This allows us to change plenty of information
     * in the model, and still use the old, pre-update model to actually make the HTTP request.
     *
     * @throws ConnectionException
     */
    public function update(Node $node): Response
    {
        return $this->getHttpClient()->post('/api/update', $node->getConfiguration());
    }
}
