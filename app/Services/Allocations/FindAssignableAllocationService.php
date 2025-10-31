<?php

namespace App\Services\Allocations;

use App\Exceptions\DisplayException;
use App\Exceptions\Service\Allocation\AutoAllocationNotEnabledException;
use App\Exceptions\Service\Allocation\CidrOutOfRangeException;
use App\Exceptions\Service\Allocation\InvalidPortMappingException;
use App\Exceptions\Service\Allocation\NoAutoAllocationSpaceAvailableException;
use App\Exceptions\Service\Allocation\PortOutOfRangeException;
use App\Exceptions\Service\Allocation\TooManyPortsInRangeException;
use App\Models\Allocation;
use App\Models\Server;
use Webmozart\Assert\Assert;

class FindAssignableAllocationService
{
    /**
     * FindAssignableAllocationService constructor.
     */
    public function __construct(private AssignmentService $service) {}

    /**
     * Finds an existing unassigned allocation and attempts to assign it to the given server.
     *
     * Always attempts to find an existing unassigned allocation first. If create_new is enabled
     * and no unassigned allocation is available, creates a new one from the configured port range.
     * If create_new is disabled, throws an exception when no unassigned allocations are available.
     *
     * @throws DisplayException
     * @throws CidrOutOfRangeException
     * @throws InvalidPortMappingException
     * @throws PortOutOfRangeException
     * @throws TooManyPortsInRangeException
     */
    public function handle(Server $server): Allocation
    {
        if (!config('panel.client_features.allocations.enabled')) {
            throw new AutoAllocationNotEnabledException();
        }

        $createNew = config('panel.client_features.allocations.create_new', true);

        // Attempt to find a given available allocation for a server. If one cannot be found
        // we will fall back to attempting to create a new allocation from the configured range
        // (only if create_new is enabled).
        //
        // Note: We use withoutGlobalScopes() to bypass Filament's tenant scoping when called
        // from the Server panel context, which would otherwise filter allocations to only
        // those belonging to the current server (making it impossible to find unassigned ones).
        /** @var Allocation|null $allocation */
        $allocation = Allocation::withoutGlobalScopes()
            ->where('node_id', $server->node_id)
            ->when($server->allocation, function ($query) use ($server) {
                $query->where('ip', $server->allocation->ip);
            })
            ->whereNull('server_id')
            ->inRandomOrder()
            ->first();

        // If create_new is disabled, only pick from existing allocations
        if (!$createNew && !$allocation) {
            throw new NoAutoAllocationSpaceAvailableException();
        }

        // If create_new is enabled, create a new allocation if none available
        $allocation = $allocation ?? $this->createNewAllocation($server);

        $allocation->update(['server_id' => $server->id]);

        return $allocation->refresh();
    }

    /**
     * Create a new allocation on the server's node with a random port from the defined range
     * in the settings. If there are no matches in that range, or something is wrong with the
     * range information provided an exception will be raised.
     *
     * @throws DisplayException
     * @throws CidrOutOfRangeException
     * @throws InvalidPortMappingException
     * @throws PortOutOfRangeException
     * @throws TooManyPortsInRangeException
     */
    protected function createNewAllocation(Server $server): Allocation
    {
        $start = config('panel.client_features.allocations.range_start', null);
        $end = config('panel.client_features.allocations.range_end', null);

        if (!$start || !$end) {
            throw new NoAutoAllocationSpaceAvailableException();
        }

        Assert::integerish($start);
        Assert::integerish($end);

        // Get all the currently allocated ports for the node so that we can figure out
        // which port might be available.
        // Use withoutGlobalScopes() to bypass tenant filtering.
        $ports = Allocation::withoutGlobalScopes()
            ->where('node_id', $server->node_id)
            ->where('ip', $server->allocation->ip)
            ->whereBetween('port', [$start, $end])
            ->pluck('port');

        // Compute the difference of the range and the currently created ports, finding
        // any port that does not already exist in the database. We will then use this
        // array of ports to create a new allocation to assign to the server.
        $available = array_diff(range($start, $end), $ports->toArray());

        // If we've already allocated all the ports, just abort.
        if (empty($available)) {
            throw new NoAutoAllocationSpaceAvailableException();
        }

        // Pick a random port out of the remaining available ports.
        /** @var int $port */
        $port = $available[array_rand($available)];

        $this->service->handle($server->node, [
            'allocation_ip' => $server->allocation->ip,
            'allocation_ports' => [$port],
        ]);

        /** @var Allocation $allocation */
        $allocation = Allocation::withoutGlobalScopes()
            ->where('node_id', $server->node_id)
            ->where('ip', $server->allocation->ip)
            ->where('port', $port)
            ->firstOrFail();

        return $allocation;
    }
}
