<?php

namespace App\Http\Controllers\Api\Client\Servers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Server;
use Illuminate\Http\JsonResponse;
use App\Facades\Activity;
use App\Models\Permission;
use App\Services\Subusers\SubuserCreationService;
use App\Repositories\Daemon\DaemonServerRepository;
use App\Transformers\Api\Client\SubuserTransformer;
use App\Http\Controllers\Api\Client\ClientApiController;
use App\Exceptions\Http\Connection\DaemonConnectionException;
use App\Http\Requests\Api\Client\Servers\Subusers\GetSubuserRequest;
use App\Http\Requests\Api\Client\Servers\Subusers\StoreSubuserRequest;
use App\Http\Requests\Api\Client\Servers\Subusers\DeleteSubuserRequest;
use App\Http\Requests\Api\Client\Servers\Subusers\UpdateSubuserRequest;

class SubuserController extends ClientApiController
{
    /**
     * SubuserController constructor.
     */
    public function __construct(
        private SubuserCreationService $creationService,
        private DaemonServerRepository $serverRepository
    ) {
        parent::__construct();
    }

    /**
     * Return the users associated with this server instance.
     */
    public function index(GetSubuserRequest $request, Server $server): array
    {
        return $this->fractal->collection($server->subusers)
            ->transformWith($this->getTransformer(SubuserTransformer::class))
            ->toArray();
    }

    /**
     * Returns a single subuser associated with this server instance.
     */
    public function view(GetSubuserRequest $request, Server $server, User $user): array
    {
        $subuser = $request->attributes->get('subuser');

        return $this->fractal->item($subuser)
            ->transformWith($this->getTransformer(SubuserTransformer::class))
            ->toArray();
    }

    /**
     * Create a new subuser for the given server.
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
     * Update a given subuser in the system for the server.
     *
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function update(UpdateSubuserRequest $request, Server $server, User $user): array
    {
        /** @var \App\Models\Subuser $subuser */
        $subuser = $request->attributes->get('subuser');

        $permissions = $this->getDefaultPermissions($request);
        $current = $subuser->permissions;

        sort($permissions);
        sort($current);

        $log = Activity::event('server:subuser.update')
            ->subject($subuser->user)
            ->property([
                'email' => $subuser->user->email,
                'old' => $current,
                'new' => $permissions,
                'revoked' => true,
            ]);

        // Only update the database and hit up the daemon instance to invalidate JTI's if the permissions
        // have actually changed for the user.
        if ($permissions !== $current) {
            $log->transaction(function ($instance) use ($request, $subuser, $server) {
                $subuser->update(['permissions' => $this->getDefaultPermissions($request)]);

                try {
                    $this->serverRepository->setServer($server)->revokeUserJTI($subuser->user_id);
                } catch (DaemonConnectionException $exception) {
                    // Don't block this request if we can't connect to the daemon instance. Chances are it is
                    // offline and the token will be invalid once daemon boots back.
                    logger()->warning($exception, ['user_id' => $subuser->user_id, 'server_id' => $server->id]);

                    $instance->property('revoked', false);
                }
            });
        }

        $log->reset();

        return $this->fractal->item($subuser->refresh())
            ->transformWith($this->getTransformer(SubuserTransformer::class))
            ->toArray();
    }

    /**
     * Removes a subusers from a server's assignment.
     */
    public function delete(DeleteSubuserRequest $request, Server $server, User $user): JsonResponse
    {
        /** @var \App\Models\Subuser $subuser */
        $subuser = $request->attributes->get('subuser');

        $log = Activity::event('server:subuser.delete')
            ->subject($subuser->user)
            ->property('email', $subuser->user->email)
            ->property('revoked', true);

        $log->transaction(function ($instance) use ($server, $subuser) {
            $subuser->delete();

            try {
                $this->serverRepository->setServer($server)->revokeUserJTI($subuser->user_id);
            } catch (DaemonConnectionException $exception) {
                // Don't block this request if we can't connect to the daemon instance.
                logger()->warning($exception, ['user_id' => $subuser->user_id, 'server_id' => $server->id]);

                $instance->property('revoked', false);
            }
        });

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Returns the default permissions for subusers and parses out any permissions
     * that were passed that do not also exist in the internally tracked list of
     * permissions.
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
