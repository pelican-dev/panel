<?php

namespace App\Http\Controllers\Api\Application\Mounts;

use App\Exceptions\Model\DataValidationException;
use App\Exceptions\Service\HasActiveServersException;
use App\Http\Controllers\Api\Application\ApplicationApiController;
use App\Http\Requests\Api\Application\Eggs\GetEggsRequest;
use App\Http\Requests\Api\Application\Mounts\DeleteMountRequest;
use App\Http\Requests\Api\Application\Mounts\GetMountRequest;
use App\Http\Requests\Api\Application\Mounts\StoreMountRequest;
use App\Http\Requests\Api\Application\Mounts\UpdateMountRequest;
use App\Http\Requests\Api\Application\Nodes\GetNodesRequest;
use App\Http\Requests\Api\Application\Servers\GetServerRequest;
use App\Models\Mount;
use App\Transformers\Api\Application\EggTransformer;
use App\Transformers\Api\Application\MountTransformer;
use App\Transformers\Api\Application\NodeTransformer;
use App\Transformers\Api\Application\ServerTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class MountController extends ApplicationApiController
{
    /**
     * List mounts
     *
     * Return all the mounts currently available on the Panel.
     *
     * @return array<array-key, mixed>
     */
    public function index(GetMountRequest $request): array
    {
        $mounts = QueryBuilder::for(Mount::class)
            ->allowedFilters(['uuid', 'name'])
            ->allowedSorts(['id', 'uuid'])
            ->paginate($request->query('per_page') ?? 50);

        return $this->fractal->collection($mounts)
            ->transformWith($this->getTransformer(MountTransformer::class))
            ->toArray();
    }

    /**
     * View mount
     *
     * Return data for a single instance of a mount.
     *
     * @return array<array-key, mixed>
     */
    public function view(GetMountRequest $request, Mount $mount): array
    {
        return $this->fractal->item($mount)
            ->transformWith($this->getTransformer(MountTransformer::class))
            ->toArray();
    }

    /**
     * Create mount
     *
     * Create a new mount on the Panel. Returns the created mount and an HTTP/201
     * status response on success.
     *
     * @throws DataValidationException
     */
    public function store(StoreMountRequest $request): JsonResponse
    {
        $model = (new Mount())->fill($request->validated());
        $model->forceFill(['uuid' => Uuid::uuid4()->toString()]);

        $model->saveOrFail();
        $mount = $model->fresh();

        return $this->fractal->item($mount)
            ->transformWith($this->getTransformer(MountTransformer::class))
            ->addMeta([
                'resource' => route('api.application.mounts.view', [
                    'mount' => $mount->id,
                ]),
            ])
            ->respond(201);
    }

    /**
     * Update mount
     *
     * Update an existing mount on the Panel.
     *
     * @return array<array-key, mixed>
     *
     * @throws Throwable
     */
    public function update(UpdateMountRequest $request, Mount $mount): array
    {
        $mount->forceFill($request->validated())->save();

        return $this->fractal->item($mount)
            ->transformWith($this->getTransformer(MountTransformer::class))
            ->toArray();
    }

    /**
     * Delete mount
     *
     * Deletes a given mount from the Panel as long as there are no servers
     * currently attached to it.
     *
     * @throws HasActiveServersException
     */
    public function delete(DeleteMountRequest $request, Mount $mount): JsonResponse
    {
        if ($mount->servers()->count() > 0) {
            throw new HasActiveServersException(trans('exceptions.mount.servers_attached'));
        }

        $mount->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * List assigned eggs
     *
     * @return array<array-key, mixed>
     */
    public function getEggs(GetEggsRequest $request, Mount $mount): array
    {
        return $this->fractal->collection($mount->eggs)
            ->transformWith($this->getTransformer(EggTransformer::class))
            ->toArray();
    }

    /**
     * List assigned nodes
     *
     * @return array<array-key, mixed>
     */
    public function getNodes(GetNodesRequest $request, Mount $mount): array
    {
        return $this->fractal->collection($mount->nodes)
            ->transformWith($this->getTransformer(NodeTransformer::class))
            ->toArray();
    }

    /**
     * List assigned servers
     *
     * @return array<array-key, mixed>
     */
    public function getServers(GetServerRequest $request, Mount $mount): array
    {
        return $this->fractal->collection($mount->servers)
            ->transformWith($this->getTransformer(ServerTransformer::class))
            ->toArray();
    }

    /**
     * Assign eggs to mount
     *
     * Adds eggs to the mount's many-to-many relation.
     *
     * @return array<array-key, mixed>
     */
    public function addEggs(Request $request, Mount $mount): array
    {
        $validatedData = $request->validate([
            'eggs' => 'required|array|exists:eggs,id',
            'eggs.*' => 'integer',
        ]);

        $mount->eggs()->attach($validatedData['eggs']);

        return $this->fractal->item($mount)
            ->transformWith($this->getTransformer(MountTransformer::class))
            ->toArray();
    }

    /**
     * Assign nodes to mount
     *
     * Adds nodes to the mount's many-to-many relation.
     *
     * @return array<array-key, mixed>
     */
    public function addNodes(Request $request, Mount $mount): array
    {
        $validatedData = $request->validate([
            'nodes' => 'required|array|exists:nodes,id',
            'nodes.*' => 'integer',
        ]);

        $mount->nodes()->attach($validatedData['nodes']);

        return $this->fractal->item($mount)
            ->transformWith($this->getTransformer(MountTransformer::class))
            ->toArray();
    }

    /**
     * Assign servers to mount
     *
     * Adds servers to the mount's many-to-many relation.
     *
     * @return array<array-key, mixed>
     */
    public function addServers(Request $request, Mount $mount): array
    {
        $validatedData = $request->validate([
            'servers' => 'required|array|exists:servers,id',
            'servers.*' => 'integer',
        ]);

        $mount->servers()->attach($validatedData['servers']);

        return $this->fractal->item($mount)
            ->transformWith($this->getTransformer(MountTransformer::class))
            ->toArray();
    }

    /**
     * Unassign egg from mount
     *
     * Deletes an egg from the mount's many-to-many relation.
     */
    public function deleteEgg(Mount $mount, int $egg_id): JsonResponse
    {
        $mount->eggs()->detach($egg_id);

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Unassign node from mount
     *
     * Deletes a node from the mount's many-to-many relation.
     */
    public function deleteNode(Mount $mount, int $node_id): JsonResponse
    {
        $mount->nodes()->detach($node_id);

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Unassign server from mount
     *
     * Deletes a server from the mount's many-to-many relation.
     */
    public function deleteServer(Mount $mount, int $server_id): JsonResponse
    {
        $mount->servers()->detach($server_id);

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
