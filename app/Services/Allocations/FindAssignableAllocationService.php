<?php

namespace App\Services\Allocations;

use App\Models\Objects\Endpoint;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use App\Models\Server;

class FindAssignableAllocationService
{
    public function __construct()
    {
    }

    public function handle(Server $server): int
    {
        abort_unless(config('panel.client_features.allocations.enabled'), 403, 'Auto Allocation is not enabled');

        return $this->createNewAllocation($server);
    }

    /**
     * Create a new allocation on the server's node with a random port from the defined range
     * in the settings. If there are no matches in that range, or something is wrong with the
     * range information provided an exception will be raised.
     */
    protected function createNewAllocation(Server $server): int
    {
        $start = config('panel.client_features.allocations.range_start');
        $end = config('panel.client_features.allocations.range_end');

        Assert::integerish($start);
        Assert::integerish($end);

        $ports = $server->node->servers
            ->reduce(fn (Collection $result, $value) => $result->merge($value), collect())
            ->map(fn (Endpoint $endpoint) => $endpoint->port)
            ->filter(fn (int $port): bool => $port >= $start && $port <= $end);

        // Compute the difference of the range and the currently created ports, finding
        // any port that does not already exist in the database. We will then use this
        // array of ports to create a new allocation to assign to the server.
        $available = array_diff(range($start, $end), $ports->toArray());

        // Pick a random port out of the remaining available ports.
        return $available[array_rand($available)];
    }
}
