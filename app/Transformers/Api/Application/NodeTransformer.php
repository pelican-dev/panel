<?php

namespace App\Transformers\Api\Application;

use App\Models\Node;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\NullResource;
use App\Services\Acl\Api\AdminAcl;

class NodeTransformer extends BaseTransformer
{
    /**
     * List of resources that can be included.
     */
    protected array $availableIncludes = ['allocations', 'servers'];

    /**
     * Return the resource name for the JSONAPI output.
     */
    public function getResourceName(): string
    {
        return Node::RESOURCE_NAME;
    }

    /**
     * Return a node transformed into a format that can be consumed by the external administrative API.
     */
    public function transform(Node $node): array
    {
        $response = collect($node->toArray())
            ->mapWithKeys(fn ($value, $key) => [snake_case($key) => $value])
            ->toArray();

        $response[$node->getUpdatedAtColumn()] = $this->formatTimestamp($node->updated_at);
        $response[$node->getCreatedAtColumn()] = $this->formatTimestamp($node->created_at);

        $resources = $node->servers()->select(['memory', 'disk', 'cpu'])->get();

        $response['allocated_resources'] = [
            'memory' => $resources->sum('memory'),
            'disk' => $resources->sum('disk'),
            'cpu' => $resources->sum('cpu'),
        ];

        return $response;
    }

    /**
     * Return the nodes associated with this location.
     *
     * @throws \App\Exceptions\Transformer\InvalidTransformerLevelException
     */
    public function includeAllocations(Node $node): Collection|NullResource
    {
        if (!$this->authorize(AdminAcl::RESOURCE_ALLOCATIONS)) {
            return $this->null();
        }

        $node->loadMissing('allocations');

        return $this->collection(
            $node->getRelation('allocations'),
            $this->makeTransformer(AllocationTransformer::class),
            'allocation'
        );
    }

    /**
     * Return the nodes associated with this location.
     *
     * @throws \App\Exceptions\Transformer\InvalidTransformerLevelException
     */
    public function includeServers(Node $node): Collection|NullResource
    {
        if (!$this->authorize(AdminAcl::RESOURCE_SERVERS)) {
            return $this->null();
        }

        $node->loadMissing('servers');

        return $this->collection(
            $node->getRelation('servers'),
            $this->makeTransformer(ServerTransformer::class),
            'server'
        );
    }
}
