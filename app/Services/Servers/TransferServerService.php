<?php

namespace App\Services\Servers;

use App\Models\Allocation;
use App\Models\Node;
use App\Models\Server;
use App\Models\ServerTransfer;
use App\Services\Nodes\NodeJWTService;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Http;
use Lcobucci\JWT\UnencryptedToken;
use Throwable;

class TransferServerService
{
    /**
     * TransferService constructor.
     */
    public function __construct(
        private ConnectionInterface $connection,
        private NodeJWTService $nodeJWTService,
    ) {}

    private function notify(ServerTransfer $transfer, UnencryptedToken $token): void
    {
        Http::daemon($transfer->oldNode)->post("/api/servers/{$transfer->server->uuid}/transfer", [
            'url' => $transfer->newNode->getConnectionAddress() . '/api/transfers',
            'token' => 'Bearer ' . $token->toString(),
            'server' => [
                'uuid' => $transfer->server->uuid,
                'start_on_completion' => false,
            ],
        ]);
    }

    /**
     * Starts a transfer of a server to a new node.
     *
     * @param  int[]  $additional_allocations
     *
     * @throws Throwable
     */
    public function handle(Server $server, int $node_id, ?int $allocation_id = null, ?array $additional_allocations = []): bool
    {
        $additional_allocations = array_map(intval(...), $additional_allocations);

        // Check if the node is viable for the transfer.
        $node = Node::query()
            ->select(['nodes.id', 'nodes.fqdn', 'nodes.scheme', 'nodes.daemon_token', 'nodes.daemon_connect', 'nodes.memory', 'nodes.disk', 'nodes.cpu', 'nodes.memory_overallocate', 'nodes.disk_overallocate', 'nodes.cpu_overallocate'])
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

        /** @var ServerTransfer $transfer */
        $transfer = $this->connection->transaction(function () use ($server, $node_id, $allocation_id, $additional_allocations) {
            // Create a new ServerTransfer entry.
            $transfer = ServerTransfer::create([
                'server_id' => $server->id,
                'old_node' => $server->node_id,
                'new_node' => $node_id,
            ]);

            if ($server->allocation_id) {
                $transfer->old_allocation = $server->allocation_id;
                $transfer->new_allocation = $allocation_id;
                $transfer->old_additional_allocations = $server->allocations->where('id', '!=', $server->allocation_id)->pluck('id')->all();
                $transfer->new_additional_allocations = $additional_allocations;

                // Add the allocations to the server, so they cannot be automatically assigned while the transfer is in progress.
                $this->assignAllocationsToServer($server, $node_id, $allocation_id, $additional_allocations);
            }

            $transfer->save();

            return $transfer;
        });

        // Generate a token for the destination node that the source node can use to authenticate with.
        $token = $this->nodeJWTService
            ->setExpiresAt(CarbonImmutable::now()->addMinutes(15))
            ->setSubject($server->uuid)
            ->handle($transfer->newNode, $server->uuid, 'sha256');

        // Notify the source node of the pending outgoing transfer.
        $this->notify($transfer, $token);

        return true;
    }

    /**
     * Assigns the specified allocations to the specified server.
     *
     * @param  int[]  $additional_allocations
     */
    private function assignAllocationsToServer(Server $server, int $node_id, int $allocation_id, array $additional_allocations): void
    {
        $allocations = $additional_allocations;
        $allocations[] = $allocation_id;

        $node = Node::findOrFail($node_id);
        $unassigned = $node->allocations()
            ->whereNull('server_id')
            ->pluck('id')
            ->toArray();

        $updateIds = [];
        foreach ($allocations as $allocation) {
            if (!in_array($allocation, $unassigned)) {
                continue;
            }

            $updateIds[] = $allocation;
        }

        if (!empty($updateIds)) {
            Allocation::whereIn('id', $updateIds)->update(['server_id' => $server->id]);
        }
    }
}
