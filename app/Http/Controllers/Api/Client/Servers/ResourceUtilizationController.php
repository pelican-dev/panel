<?php

namespace App\Http\Controllers\Api\Client\Servers;

use Carbon\Carbon;
use App\Models\Server;
use Illuminate\Cache\Repository;
use App\Transformers\Api\Client\StatsTransformer;
use App\Repositories\Daemon\DaemonServerRepository;
use App\Http\Controllers\Api\Client\ClientApiController;
use App\Http\Requests\Api\Client\Servers\GetServerRequest;

class ResourceUtilizationController extends ClientApiController
{
    /**
     * ResourceUtilizationController constructor.
     */
    public function __construct(private Repository $cache, private DaemonServerRepository $repository)
    {
        parent::__construct();
    }

    /**
     * Return the current resource utilization for a server. This value is cached for up to
     * 20 seconds at a time to ensure that repeated requests to this endpoint do not cause
     * a flood of unnecessary API calls.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function __invoke(GetServerRequest $request, Server $server): array
    {
        $key = "resources:$server->uuid";
        $stats = $this->cache->remember($key, Carbon::now()->addSeconds(20), function () use ($server) {
            return $this->repository->setServer($server)->getDetails();
        });

        return $this->fractal->item($stats)
            ->transformWith($this->getTransformer(StatsTransformer::class))
            ->toArray();
    }
}
