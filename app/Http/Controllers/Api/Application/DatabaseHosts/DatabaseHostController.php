<?php

namespace App\Http\Controllers\Api\Application\DatabaseHosts;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Models\DatabaseHost;
use Spatie\QueryBuilder\QueryBuilder;
use App\Services\Databases\Hosts\HostUpdateService;
use App\Services\Databases\Hosts\HostCreationService;
use App\Transformers\Api\Application\DatabaseHostTransformer;
use App\Http\Controllers\Api\Application\ApplicationApiController;
use App\Http\Requests\Api\Application\DatabaseHosts\GetDatabaseHostRequest;
use App\Http\Requests\Api\Application\DatabaseHosts\StoreDatabaseHostRequest;
use App\Http\Requests\Api\Application\DatabaseHosts\DeleteDatabaseHostRequest;
use App\Http\Requests\Api\Application\DatabaseHosts\UpdateDatabaseHostRequest;

class DatabaseHostController extends ApplicationApiController
{
    /**
     * DatabaseHostController constructor.
     */
    public function __construct(
        private HostCreationService $creationService,
        private HostUpdateService $updateService
    ) {
        parent::__construct();
    }

    /**
     * List database hosts
     *
     * Return all the database hosts currently registered on the Panel.
     *
     * @return array<mixed>
     */
    public function index(GetDatabaseHostRequest $request): array
    {
        $databases = QueryBuilder::for(DatabaseHost::query())
            ->allowedFilters(['name', 'host'])
            ->allowedSorts(['id', 'name', 'host'])
            ->paginate($request->query('per_page') ?? 10);

        return $this->fractal->collection($databases)
            ->transformWith($this->getTransformer(DatabaseHostTransformer::class))
            ->toArray();
    }

    /**
     * View database host
     *
     * Return a single database host.
     *
     * @return array<mixed>
     */
    public function view(GetDatabaseHostRequest $request, DatabaseHost $databaseHost): array
    {
        return $this->fractal->item($databaseHost)
            ->transformWith($this->getTransformer(DatabaseHostTransformer::class))
            ->toArray();
    }

    /**
     * Create database host
     *
     * Store a new database host on the Panel and return an HTTP/201 response code with the
     * new database host attached.
     *
     * @throws \Throwable
     */
    public function store(StoreDatabaseHostRequest $request): JsonResponse
    {
        $databaseHost = $this->creationService->handle($request->validated());

        return $this->fractal->item($databaseHost)
            ->transformWith($this->getTransformer(DatabaseHostTransformer::class))
            ->addMeta([
                'resource' => route('api.application.databases.view', [
                    'database_host' => $databaseHost->id,
                ]),
            ])
            ->respond(201);
    }

    /**
     * Update database host
     *
     * Update a database host on the Panel and return the updated record to the user.
     *
     * @return array<mixed>
     *
     * @throws \Throwable
     */
    public function update(UpdateDatabaseHostRequest $request, DatabaseHost $databaseHost): array
    {
        $databaseHost = $this->updateService->handle($databaseHost->id, $request->validated());

        return $this->fractal->item($databaseHost)
            ->transformWith($this->getTransformer(DatabaseHostTransformer::class))
            ->toArray();
    }

    /**
     * Delete database host
     *
     * Delete a database host from the Panel.
     *
     * @throws \Exception
     */
    public function delete(DeleteDatabaseHostRequest $request, DatabaseHost $databaseHost): Response
    {
        $databaseHost->delete();

        return $this->returnNoContent();
    }
}
