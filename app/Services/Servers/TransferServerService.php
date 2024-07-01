<?php

namespace App\Services\Servers;

use App\Exceptions\Http\Connection\DaemonConnectionException;
use App\Models\Node;
use App\Models\Server;
use App\Models\ServerTransfer;
use App\Services\Nodes\NodeJWTService;
use Carbon\CarbonImmutable;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Http;
use Lcobucci\JWT\Token\Plain;

class TransferServerService
{
    /**
     * TransferService constructor.
     */
    public function __construct(
        private ConnectionInterface $connection,
        private NodeJWTService $nodeJWTService,
    ) {
    }

    private function notify(Server $server, Plain $token): void
    {
        try {
            Http::daemon($server->node)->post('/api/transfer', [
                'json' => [
                    'server_id' => $server->uuid,
                    'url' => $server->node->getConnectionAddress() . "/api/servers/$server->uuid/archive",
                    'token' => 'Bearer ' . $token->toString(),
                    'server' => [
                        'uuid' => $server->uuid,
                        'start_on_completion' => false,
                    ],
                ],
            ])->toPsrResponse();
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }

    /**
     * Starts a transfer of a server to a new node.
     *
     * @throws \Throwable
     */
    public function handle(Server $server, array $data): bool
    {
        $node_id = $data['node_id'];

        // Check if the node is viable for the transfer.
        $node = Node::query()
            ->select(['nodes.id', 'nodes.fqdn', 'nodes.scheme', 'nodes.daemon_token', 'nodes.daemon_listen', 'nodes.memory', 'nodes.disk', 'nodes.cpu', 'nodes.memory_overallocate', 'nodes.disk_overallocate', 'nodes.cpu_overallocate'])
            ->withSum('servers', 'disk')
            ->withSum('servers', 'memory')
            ->withSum('servers', 'cpu')
            ->leftJoin('servers', 'servers.node_id', '=', 'nodes.id')
            ->where('nodes.id', $node_id)
            ->first();

        if (!$node->isViable($server->memory, $server->disk, $server->cpu)) {
            return false;
        }

        $server->validateTransferState();

        $this->connection->transaction(function () use ($server, $node_id) {
            $transfer = new ServerTransfer();

            $transfer->server_id = $server->id;
            $transfer->old_node = $server->node_id;
            $transfer->new_node = $node_id;

            $transfer->save();

            // Generate a token for the destination node that the source node can use to authenticate with.
            $token = $this->nodeJWTService
                ->setExpiresAt(CarbonImmutable::now()->addMinutes(15))
                ->setSubject($server->uuid)
                ->handle($transfer->newNode, $server->uuid, 'sha256');

            // Notify the source node of the pending outgoing transfer.
            $this->notify($server, $token);

            return $transfer;
        });

        return true;
    }
}
