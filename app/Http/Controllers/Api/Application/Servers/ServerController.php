<?php

namespace App\Http\Controllers\Api\Application\Servers;

use Illuminate\Http\Response;
use App\Models\Server;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;
use App\Services\Servers\ServerCreationService;
use App\Services\Servers\ServerDeletionService;
use App\Transformers\Api\Application\ServerTransformer;
use App\Http\Requests\Api\Application\Servers\GetServerRequest;
use App\Http\Requests\Api\Application\Servers\GetServersRequest;
use App\Http\Requests\Api\Application\Servers\ServerWriteRequest;
use App\Http\Requests\Api\Application\Servers\StoreServerRequest;
use App\Http\Controllers\Api\Application\ApplicationApiController;
use Dedoc\Scramble\Attributes\Group;

#[Group('Server', weight: 0)]
class ServerController extends ApplicationApiController
{
    /**
     * ServerController constructor.
     */
    public function __construct(
        private ServerCreationService $creationService,
        private ServerDeletionService $deletionService
    ) {
        parent::__construct();
    }

    /**
     * List servers
     *
     * Return all the servers that currently exist on the Panel.
     *
     * @return array<array-key, mixed>
     */
    public function index(GetServersRequest $request): array
    {
        $servers = QueryBuilder::for(Server::class)
            ->allowedFilters(['uuid', 'uuid_short', 'name', 'description', 'image', 'external_id'])
            ->allowedSorts(['id', 'uuid'])
            ->paginate($request->query('per_page') ?? 50);

        return $this->fractal->collection($servers)
            ->transformWith($this->getTransformer(ServerTransformer::class))
            ->toArray();
    }

    /**
     * Create server
     *
     * Create a new server on the system.
     *
     * @throws \Throwable
     * @throws \Illuminate\Validation\ValidationException
     * @throws \App\Exceptions\DisplayException
     * @throws \App\Exceptions\Model\DataValidationException
     * @throws \App\Exceptions\Service\Deployment\NoViableAllocationException
     */
    public function store(StoreServerRequest $request): JsonResponse
    {
        $server = $this->creationService->handle($request->validated(), $request->getDeploymentObject());

        return $this->fractal->item($server)
            ->transformWith($this->getTransformer(ServerTransformer::class))
            ->respond(201);
    }

    /**
     * View server
     *
     * Show a single server transformed for the application API.
     *
     * @return array<array-key, mixed>
     */
    public function view(GetServerRequest $request, Server $server): array
    {
        return $this->fractal->item($server)
            ->transformWith($this->getTransformer(ServerTransformer::class))
            ->toArray();
    }

    /**
     * Delete server
     *
     * Deletes a server.
     *
     * @throws \App\Exceptions\DisplayException
     */
    public function delete(ServerWriteRequest $request, Server $server, string $force = ''): Response
    {
        $this->deletionService->withForce($force === 'force')->handle($server);

        return $this->returnNoContent();
    }
}
