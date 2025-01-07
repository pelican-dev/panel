<?php

namespace App\Http\Controllers\Api\Application\Servers;

use App\Exceptions\Model\DataValidationException;
use App\Models\User;
use App\Models\Server;
use App\Services\Servers\StartupModificationService;
use App\Transformers\Api\Application\ServerTransformer;
use App\Http\Controllers\Api\Application\ApplicationApiController;
use App\Http\Requests\Api\Application\Servers\UpdateServerStartupRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Validation\ValidationException;

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
