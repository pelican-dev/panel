<?php

namespace App\Services\Deployment;

use App\Models\Node;
use Illuminate\Support\Collection;

class FindViableNodesService
{
    /**
     * Returns a collection of nodes that meet the provided requirements and can then
     * be passed to the AllocationSelectionService to return a single allocation.
     *
     * This functionality is used for automatic deployments of servers and will
     * attempt to find all nodes in the defined locations that meet the memory, disk
     * and cpu availability requirements. Any nodes not meeting those requirements
     * are tossed out, as are any nodes marked as non-public, meaning automatic
     * deployments should not be done against them.
     *
     * @param  string[]  $tags
     */
    public function handle(int $memory = 0, int $disk = 0, int $cpu = 0, array $tags = []): Collection
    {
        $nodes = Node::query()
            ->withSum('servers', 'memory')
            ->withSum('servers', 'disk')
            ->withSum('servers', 'cpu')
            ->where('public', true)
            ->get();

        return $nodes
            ->filter(fn (Node $node) => !$tags || collect($node->tags)->intersect($tags)->isNotEmpty())
            ->filter(fn (Node $node) => $node->isViable($memory, $disk, $cpu));
    }
}
