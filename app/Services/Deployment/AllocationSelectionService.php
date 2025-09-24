<?php

namespace App\Services\Deployment;

use App\Exceptions\DisplayException;
use App\Exceptions\Service\Deployment\NoViableAllocationException;
use App\Models\Allocation;
use App\Models\Node;
use App\Services\Allocations\AssignmentService;

class AllocationSelectionService
{
    protected bool $dedicated = false;

    /** @var array<int> */
    protected array $nodes = [];

    /** @var array<string|int> */
    protected array $ports = [];

    /**
     * Toggle if the selected allocation should be the only allocation belonging
     * to the given IP address. If true an allocation will not be selected if an IP
     * already has another server set to use on if its allocations.
     */
    public function setDedicated(bool $dedicated): self
    {
        $this->dedicated = $dedicated;

        return $this;
    }

    /**
     * A list of node IDs that should be used when selecting an allocation. If empty, all
     * nodes will be used to filter with.
     *
     * @param  array<int>  $nodes
     */
    public function setNodes(array $nodes): self
    {
        $this->nodes = $nodes;

        return $this;
    }

    /**
     * An array of individual ports or port ranges to use when selecting an allocation. If
     * empty, all ports will be considered when finding an allocation. If set, only ports appearing
     * in the array or range will be used.
     *
     * @param  array<string|int>  $ports
     *
     * @throws DisplayException
     */
    public function setPorts(array $ports): self
    {
        $stored = [];
        foreach ($ports as $port) {
            if (is_digit($port)) {
                $stored[] = $port;
            }

            // Ranges are stored in the ports array as an array which can be
            // better processed in the repository.
            if (preg_match(AssignmentService::PORT_RANGE_REGEX, $port, $matches)) {
                if (abs((int) $matches[2] - (int) $matches[1]) > AssignmentService::PORT_RANGE_LIMIT) {
                    throw new DisplayException(trans('exceptions.allocations.too_many_ports'));
                }

                $stored[] = [$matches[1], $matches[2]];
            }
        }

        $this->ports = $stored;

        return $this;
    }

    /**
     * Return a single allocation that should be used as the default allocation for a server.
     *
     * @throws NoViableAllocationException
     */
    public function handle(): Allocation
    {
        $allocation = $this->getRandomAllocation($this->nodes, $this->ports, $this->dedicated);

        if (is_null($allocation)) {
            throw new NoViableAllocationException(trans('exceptions.deployment.no_viable_allocations'));
        }

        return $allocation;
    }

    /**
     * Return a single allocation from those meeting the requirements.
     *
     * @param  array<int>  $nodes
     * @param  array<int|string|array<int|string>>  $ports
     */
    private function getRandomAllocation(array $nodes = [], array $ports = [], bool $dedicated = false): ?Allocation
    {
        $query = Allocation::query()
            ->whereNull('server_id')
            ->whereIn('node_id', $nodes);

        if (!empty($ports)) {
            $query->where(function ($inner) use ($ports) {
                $whereIn = [];
                foreach ($ports as $port) {
                    if (is_array($port)) {
                        $inner->orWhereBetween('port', $port);

                        continue;
                    }

                    $whereIn[] = $port;
                }

                if (!empty($whereIn)) {
                    $inner->orWhereIn('port', $whereIn);
                }
            });
        }

        // If this allocation should not be shared with any other servers get
        // the data and modify the query as necessary,
        if ($dedicated) {
            $discard = $this->getDiscardableDedicatedAllocations($nodes);

            if (!empty($discard)) {
                $query->whereNotIn('ip', $discard);
            }
        }

        return $query->inRandomOrder()->first();
    }

    /**
     * Return a result set of node ips that already have at least one
     * server assigned to that IP. This allows for filtering out sets for
     * dedicated allocation IPs.
     *
     * If an array of nodes is passed the results will be limited to allocations
     * in those nodes.
     *
     * @param  array<int>  $nodes
     * @return array<Node>
     */
    private function getDiscardableDedicatedAllocations(array $nodes = []): array
    {
        $query = Allocation::query()->whereNotNull('server_id');

        if (!empty($nodes)) {
            $query->whereIn('node_id', $nodes);
        }

        return $query
            ->groupBy('ip')
            ->pluck('ip')
            ->toArray();
    }
}
