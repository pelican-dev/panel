<?php

namespace App\Services\Allocations;

use Webmozart\Assert\Assert;
use App\Models\Server;
use App\Exceptions\Service\Allocation\AutoAllocationNotEnabledException;
use App\Exceptions\Service\Allocation\NoAutoAllocationSpaceAvailableException;

class FindAssignableAllocationService
{
    public function __construct()
    {
    }

    /**
     * @throws AutoAllocationNotEnabledException
     * @throws NoAutoAllocationSpaceAvailableException
     */
    public function handle(Server $server): int
    {
        if (!config('panel.client_features.allocations.enabled')) {
            throw new AutoAllocationNotEnabledException();
        }

        return $this->createNewAllocation($server);
    }

    /**
     * Create a new allocation on the server's node with a random port from the defined range
     * in the settings. If there are no matches in that range, or something is wrong with the
     * range information provided an exception will be raised.
     *
     * @throws NoAutoAllocationSpaceAvailableException
     */
    protected function createNewAllocation(Server $server): int
    {
        $start = config('panel.client_features.allocations.range_start');
        $end = config('panel.client_features.allocations.range_end');

        if (!$start || !$end) {
            throw new NoAutoAllocationSpaceAvailableException();
        }

        Assert::integerish($start);
        Assert::integerish($end);

        // Get all the currently allocated ports for the node so that we can figure out which port might be available.
        $ports = $server->node->allocations()
            ->where('ip', $server->allocation->ip)
            ->whereBetween('port', [$start, $end])
            ->pluck('port');

        // Compute the difference of the range and the currently created ports, finding
        // any port that does not already exist in the database. We will then use this
        // array of ports to create a new allocation to assign to the server.
        $available = array_diff(range($start, $end), $ports->toArray());

        // Pick a random port out of the remaining available ports.
        /** @var int $port */
        return $available[array_rand($available)];
    }
}
