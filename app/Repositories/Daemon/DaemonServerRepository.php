<?php

namespace App\Repositories\Daemon;

use App\Enums\ContainerStatus;
use App\Enums\HttpStatusCode;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Http\Client\RequestException;
use Webmozart\Assert\Assert;
use App\Models\Server;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TransferException;
use App\Exceptions\Http\Connection\DaemonConnectionException;

class DaemonServerRepository extends DaemonRepository
{
    /**
     * Returns details about a server from the Daemon instance.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function getDetails(): array
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            return $this->getHttpClient()->get(
                sprintf('/api/servers/%s', $this->server->uuid)
            )->throw()->json();
        } catch (RequestException $exception) {
            $cfId = $exception->response->header('Cf-Ray');
            $cfCache = $exception->response->header('Cf-Cache-Status');
            $code = HttpStatusCode::tryFrom($exception->getCode());

            $requestFromCloudflare = !empty($cfId);
            $requestCachedFromCloudflare = !empty($cfCache);
            $requestBadGateway = $code === HttpStatusCode::BadGateway;

            if ($requestBadGateway && $requestFromCloudflare && !$requestCachedFromCloudflare) {
                Notification::make()
                    ->title('Cloudflare Issue')
                    ->body('Your Node is not accessible by Cloudflare')
                    ->danger()
                    ->send();
            }
        } catch (Exception $exception) {
            report($exception);
        }

        return ['state' => ContainerStatus::Missing->value];
    }

    /**
     * Creates a new server on the daemon.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function create(bool $startOnCompletion = true): void
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            $response = $this->getHttpClient()->post('/api/servers', [
                'uuid' => $this->server->uuid,
                'start_on_completion' => $startOnCompletion,
            ]);
        } catch (GuzzleException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }

    /**
     * Triggers a server sync on daemon.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function sync(): void
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            $this->getHttpClient()->post("/api/servers/{$this->server->uuid}/sync");
        } catch (GuzzleException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }

    /**
     * Delete a server from the daemon, forcibly if passed.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function delete(): void
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            $this->getHttpClient()->delete('/api/servers/' . $this->server->uuid);
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }

    /**
     * Reinstall a server on the daemon.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function reinstall(): void
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            $this->getHttpClient()->post(sprintf(
                '/api/servers/%s/reinstall',
                $this->server->uuid
            ));
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }

    /**
     * Requests the daemon to create a full archive of the server. Once the daemon is finished
     * they will send a POST request to "/api/remote/servers/{uuid}/archive" with a boolean.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function requestArchive(): void
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            $this->getHttpClient()->post(sprintf(
                '/api/servers/%s/archive',
                $this->server->uuid
            ));
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }

    /**
     * Cancels a server transfer.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function cancelTransfer(): void
    {
        Assert::isInstanceOf($this->server, Server::class);

        if ($transfer = $this->server->transfer) {
            // Source node
            $this->setNode($transfer->oldNode);

            try {
                $this->getHttpClient()->delete(sprintf(
                    '/api/servers/%s/transfer',
                    $this->server->uuid
                ));
            } catch (TransferException $exception) {
                throw new DaemonConnectionException($exception);
            }

            // Destination node
            $this->setNode($transfer->newNode);

            try {
                $this->getHttpClient()->delete('/api/transfer', [
                    'json' => [
                        'server_id' => $this->server->uuid,
                        'server' => [
                            'uuid' => $this->server->uuid,
                        ],
                    ],
                ]);
            } catch (TransferException $exception) {
                throw new DaemonConnectionException($exception);
            }
        }
    }

    /**
     * Revokes a single user's JTI by using their ID. This is simply a helper function to
     * make it easier to revoke tokens on the fly. This ensures that the JTI key is formatted
     * correctly and avoids any costly mistakes in the codebase.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function revokeUserJTI(int $id): void
    {
        Assert::isInstanceOf($this->server, Server::class);

        $this->revokeJTIs([md5($id . $this->server->uuid)]);
    }

    /**
     * Revokes an array of JWT JTI's by marking any token generated before the current time on
     * the daemon instance as being invalid.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    protected function revokeJTIs(array $jtis): void
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            $this->getHttpClient()
                ->post(sprintf('/api/servers/%s/ws/deny', $this->server->uuid), [
                    'jtis' => $jtis,
                ]);
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }
}
