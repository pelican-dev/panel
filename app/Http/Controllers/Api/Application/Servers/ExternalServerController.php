<?php

namespace App\Http\Controllers\Api\Application\Servers;

use App\Models\Server;
use Dedoc\Scramble\Attributes\Group;
use App\Transformers\Api\Application\ServerTransformer;
use App\Http\Controllers\Api\Application\ApplicationApiController;
use App\Http\Requests\Api\Application\Servers\GetExternalServerRequest;

#[Group('Server', weight: 1)]
class ExternalServerController extends ApplicationApiController
{
    /**
     * View server (external id).
     *
     * Retrieve a specific server from the database using its external ID.
     */
    public function index(GetExternalServerRequest $request, string $external_id): array
    {
        $server = Server::query()->where('external_id', $external_id)->firstOrFail();

        return $this->fractal->item($server)
            ->transformWith($this->getTransformer(ServerTransformer::class))
            ->toArray();
    }
}
