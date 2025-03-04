<?php

namespace App\Http\Controllers\Api\Client\Servers;

use App\Models\User;
use App\Services\Subusers\SubuserDeletionService;
use App\Services\Subusers\SubuserUpdateService;
use Illuminate\Http\Request;
use App\Models\Server;
use Illuminate\Http\JsonResponse;
use App\Facades\Activity;
use App\Models\Permission;
use App\Services\Subusers\SubuserCreationService;
use App\Transformers\Api\Client\SubuserTransformer;
use App\Http\Controllers\Api\Client\ClientApiController;
use App\Http\Requests\Api\Client\Servers\Subusers\GetSubuserRequest;
use App\Http\Requests\Api\Client\Servers\Subusers\StoreSubuserRequest;
use App\Http\Requests\Api\Client\Servers\Subusers\DeleteSubuserRequest;
use App\Http\Requests\Api\Client\Servers\Subusers\UpdateSubuserRequest;
use Dedoc\Scramble\Attributes\Group;

#[Group('Server - Subuser')]
class SubuserController extends ClientApiController
{
    /**
     * SubuserController constructor.
     */
    public function __construct(
        private SubuserCreationService $creationService,
        private SubuserUpdateService $updateService,
        private SubuserDeletionService $deletionService
    ) {
        parent::__construct();
    }

    /**
     * List subusers
     *
     * Return the users associated with this server instance.
     *
     * @return array<array-key, mixed>
     */
    public function index(GetSubuserRequest $request, Server $server): array
    {
        return $this->fractal->collection($server->subusers)
            ->transformWith($this->getTransformer(SubuserTransformer::class))
            ->toArray();
    }

    /**
     * View subusers
     *
     * Returns a single subuser associated with this server instance.
     *
     * @return array<array-key, mixed>
     */
    public function view(GetSubuserRequest $request, Server $server, User $user): array
    {
        $subuser = $request->attributes->get('subuser');

        return $this->fractal->item($subuser)
            ->transformWith($this->getTransformer(SubuserTransformer::class))
            ->toArray();
    }

    /**
     * Create subuser
     *
     * Create a new subuser for the given server.
     *
     * @return array<array-key, mixed>
     *
     * @throws \App\Exceptions\Model\DataValidationException
     * @throws \App\Exceptions\Service\Subuser\ServerSubuserExistsException
     * @throws \App\Exceptions\Service\Subuser\UserIsServerOwnerException
     * @throws \Throwable
     */
    public function store(StoreSubuserRequest $request, Server $server): array
    {
        $response = $this->creationService->handle(
            $server,
            $request->input('email'),
            $this->getDefaultPermissions($request)
        );

        Activity::event('server:subuser.create')
            ->subject($response->user)
            ->property(['email' => $request->input('email'), 'permissions' => $this->getDefaultPermissions($request)])
            ->log();

        return $this->fractal->item($response)
            ->transformWith($this->getTransformer(SubuserTransformer::class))
            ->toArray();
    }

    /**
     * Update subuser
     *
     * Update a given subuser in the system for the server.
     *
     * @return array<array-key, mixed>
     *
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function update(UpdateSubuserRequest $request, Server $server, User $user): array
    {
        /** @var \App\Models\Subuser $subuser */
        $subuser = $request->attributes->get('subuser');

        $this->updateService->handle($subuser, $server, $this->getDefaultPermissions($request));

        return $this->fractal->item($subuser->refresh())
            ->transformWith($this->getTransformer(SubuserTransformer::class))
            ->toArray();
    }

    /**
     * Delete subuser
     *
     * Removes a subusers from a server's assignment.
     */
    public function delete(DeleteSubuserRequest $request, Server $server, User $user): JsonResponse
    {
        /** @var \App\Models\Subuser $subuser */
        $subuser = $request->attributes->get('subuser');

        $this->deletionService->handle($subuser, $server);

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Returns the default permissions for subusers and parses out any permissions
     * that were passed that do not also exist in the internally tracked list of
     * permissions.
     *
     * @return array<array-key, mixed>
     */
    protected function getDefaultPermissions(Request $request): array
    {
        $allowed = Permission::permissions()
            ->map(function ($value, $prefix) {
                return array_map(function ($value) use ($prefix) {
                    return "$prefix.$value";
                }, array_keys($value['keys']));
            })
            ->flatten()
            ->all();

        $cleaned = array_intersect($request->input('permissions') ?? [], $allowed);

        return array_unique(array_merge($cleaned, [Permission::ACTION_WEBSOCKET_CONNECT]));
    }
}
