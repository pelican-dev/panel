<?php

namespace App\Http\Controllers\Api\Application\Servers;

use App\Models\User;
use App\Models\Server;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Client\ConnectionException;
use App\Exceptions\Model\DataValidationException;
use App\Services\Servers\StartupModificationService;
use App\Transformers\Api\Application\ServerTransformer;
use App\Http\Controllers\Api\Application\ApplicationApiController;
use App\Http\Requests\Api\Application\Servers\UpdateServerStartupRequest;

#[Group('Server', weight: 3)]
class StartupController extends ApplicationApiController
{
    /**
     * StartupController constructor.
     */
    public function __construct(private StartupModificationService $modificationService)
    {
        parent::__construct();
    }

    /**
     * Update startup.
     *
     * Update the startup and environment settings for a specific server.
     *
     * @throws ValidationException
     * @throws ConnectionException
     * @throws DataValidationException
     */
    public function index(UpdateServerStartupRequest $request, Server $server): array
    {
        $server = $this->modificationService
            ->setUserLevel(User::USER_LEVEL_ADMIN)
            ->handle($server, $request->validated());

        return $this->fractal->item($server)
            ->transformWith($this->getTransformer(ServerTransformer::class))
            ->toArray();
    }
}
