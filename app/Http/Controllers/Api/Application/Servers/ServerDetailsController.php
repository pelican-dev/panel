<?php

namespace App\Http\Controllers\Api\Application\Servers;

use App\Models\Server;
use App\Services\Servers\BuildModificationService;
use App\Services\Servers\DetailsModificationService;
use App\Transformers\Api\Application\ServerTransformer;
use App\Http\Controllers\Api\Application\ApplicationApiController;
use App\Http\Requests\Api\Application\Servers\UpdateServerDetailsRequest;
use App\Http\Requests\Api\Application\Servers\UpdateServerBuildConfigurationRequest;
use Dedoc\Scramble\Attributes\Group;

#[Group('Server', weight: 2)]
class ServerDetailsController extends ApplicationApiController
{
    /**
     * ServerDetailsController constructor.
     */
    public function __construct(
        private BuildModificationService $buildModificationService,
        private DetailsModificationService $detailsModificationService
    ) {
        parent::__construct();
    }

    /**
     * Update details
     *
     * Update the details for a specific server.
     *
     * @return array<array-key, mixed>
     *
     * @throws \App\Exceptions\DisplayException
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function details(UpdateServerDetailsRequest $request, Server $server): array
    {
        /** @var array<array-key, mixed> $validated */
        $validated = $request->validated();

        $updated = $this->detailsModificationService->returnUpdatedModel()->handle(
            $server,
            $validated,
        );

        return $this->fractal->item($updated)
            ->transformWith($this->getTransformer(ServerTransformer::class))
            ->toArray();
    }

    /**
     * Update build
     *
     * Update the build details for a specific server.
     *
     * @return array<array-key, mixed>
     *
     * @throws \App\Exceptions\DisplayException
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function build(UpdateServerBuildConfigurationRequest $request, Server $server): array
    {
        $server = $this->buildModificationService->handle($server, $request->validated());

        return $this->fractal->item($server)
            ->transformWith($this->getTransformer(ServerTransformer::class))
            ->toArray();
    }
}
