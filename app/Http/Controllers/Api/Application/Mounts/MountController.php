<?php

namespace App\Http\Controllers\Api\Application\Mounts;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Translation\Translator;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Mount;
use App\Http\Controllers\Api\Application\ApplicationApiController;
use App\Transformers\Api\Application\MountTransformer;
use App\Http\Requests\Api\Application\Mounts\GetMountRequest;
use App\Http\Requests\Api\Application\Mounts\StoreMountRequest;
use App\Http\Requests\Api\Application\Mounts\DeleteMountRequest;
use App\Http\Requests\Api\Application\Mounts\UpdateMountRequest;
use App\Exceptions\Service\HasActiveServersException;

class MountController extends ApplicationApiController
{
    /**
     * MountController constructor.
     */
    public function __construct(
        protected Translator $translator
    ) {
        parent::__construct();
    }

    /**
     * Return all the mounts currently available on the Panel.
     */
    public function index(GetMountRequest $request): array
    {
        $mounts = QueryBuilder::for(Mount::query())
            ->allowedFilters(['uuid', 'name'])
            ->allowedSorts(['id', 'uuid'])
            ->paginate($request->query('per_page') ?? 50);

        return $this->fractal->collection($mounts)
            ->transformWith($this->getTransformer(MountTransformer::class))
            ->toArray();
    }

    /**
     * Return data for a single instance of a mount.
     */
    public function view(GetMountRequest $request, Mount $mount): array
    {
        return $this->fractal->item($mount)
            ->transformWith($this->getTransformer(MountTransformer::class))
            ->toArray();
    }

    /**
     * Create a new mount on the Panel. Returns the created mount and an HTTP/201
     * status response on success.
     *
     * @throws \App\Exceptions\Model\DataValidationException
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
     * Update an existing mount on the Panel.
     *
     * @throws \Throwable
     */
    public function update(UpdateMountRequest $request, Mount $mount): array
    {
        $mount->forceFill($request->validated())->save();

        return $this->fractal->item($mount)
            ->transformWith($this->getTransformer(MountTransformer::class))
            ->toArray();
    }

    /**
     * Deletes a given mount from the Panel as long as there are no servers
     * currently attached to it.
     *
     * @throws \App\Exceptions\Service\HasActiveServersException
     */
    public function delete(DeleteMountRequest $request, Mount $mount): JsonResponse
    {
        if ($mount->servers()->count() > 0) {
            throw new HasActiveServersException($this->translator->get('exceptions.mount.servers_attached'));
        }

        $mount->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Adds eggs to the mount's many-to-many relation.
     */
    public function addEggs(Request $request, Mount $mount): array
    {
        $validatedData = $request->validate([
            'eggs' => 'required|exists:eggs,id',
        ]);

        $eggs = $validatedData['eggs'] ?? [];
        if (count($eggs) > 0) {
            $mount->eggs()->attach($eggs);
        }

        return $this->fractal->item($mount)
            ->transformWith($this->getTransformer(MountTransformer::class))
            ->toArray();
    }

    /**
     * Adds nodes to the mount's many-to-many relation.
     */
    public function addNodes(Request $request, Mount $mount): array
    {
        $data = $request->validate(['nodes' => 'required|exists:nodes,id']);

        $nodes = $data['nodes'] ?? [];
        if (count($nodes) > 0) {
            $mount->nodes()->attach($nodes);
        }

        return $this->fractal->item($mount)
            ->transformWith($this->getTransformer(MountTransformer::class))
            ->toArray();
    }

    /**
     * Deletes an egg from the mount's many-to-many relation.
     */
    public function deleteEgg(Mount $mount, int $egg_id): JsonResponse
    {
        $mount->eggs()->detach($egg_id);

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Deletes a node from the mount's many-to-many relation.
     */
    public function deleteNode(Mount $mount, int $node_id): JsonResponse
    {
        $mount->nodes()->detach($node_id);

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
