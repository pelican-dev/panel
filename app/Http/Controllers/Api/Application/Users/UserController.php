<?php

namespace App\Http\Controllers\Api\Application\Users;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;
use App\Services\Users\UserUpdateService;
use App\Services\Users\UserCreationService;
use App\Transformers\Api\Application\UserTransformer;
use App\Http\Requests\Api\Application\Users\GetUsersRequest;
use App\Http\Requests\Api\Application\Users\StoreUserRequest;
use App\Http\Requests\Api\Application\Users\DeleteUserRequest;
use App\Http\Requests\Api\Application\Users\UpdateUserRequest;
use App\Http\Controllers\Api\Application\ApplicationApiController;
use App\Http\Requests\Api\Application\Users\AssignUserRolesRequest;
use App\Models\Role;
use Dedoc\Scramble\Attributes\Group;

#[Group('User', weight: 0)]
class UserController extends ApplicationApiController
{
    /**
     * UserController constructor.
     */
    public function __construct(
        private UserCreationService $creationService,
        private UserUpdateService $updateService
    ) {
        parent::__construct();
    }

    /**
     * List users
     *
     * Handle request to list all users on the panel. Returns a JSON-API representation
     * of a collection of users including any defined relations passed in
     * the request.
     *
     * @return array<array-key, mixed>
     */
    public function index(GetUsersRequest $request): array
    {
        $users = QueryBuilder::for(User::query())
            ->allowedFilters(['email', 'uuid', 'username', 'external_id'])
            ->allowedSorts(['id', 'uuid'])
            ->paginate($request->query('per_page') ?? 50);

        return $this->fractal->collection($users)
            ->transformWith($this->getTransformer(UserTransformer::class))
            ->toArray();
    }

    /**
     * View user
     *
     * Handle a request to view a single user. Includes any relations that
     * were defined in the request.
     *
     * @return array<array-key, mixed>
     */
    public function view(GetUsersRequest $request, User $user): array
    {
        return $this->fractal->item($user)
            ->transformWith($this->getTransformer(UserTransformer::class))
            ->toArray();
    }

    /**
     * Update user
     *
     * Update an existing user on the system and return the response. Returns the
     * updated user model response on success. Supports handling of token revocation
     * errors when switching a user from an admin to a normal user.
     *
     * Revocation errors are returned under the 'revocation_errors' key in the response
     * meta. If there are no errors this is an empty array.
     *
     * @return array<array-key, mixed>
     *
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function update(UpdateUserRequest $request, User $user): array
    {
        $this->updateService->setUserLevel(User::USER_LEVEL_ADMIN);
        $user = $this->updateService->handle($user, $request->validated());

        $response = $this->fractal->item($user)
            ->transformWith($this->getTransformer(UserTransformer::class));

        return $response->toArray();
    }

    /**
     * Assign role to user
     *
     * Assign roles to a user.
     *
     * @return array<array-key, mixed>
     */
    public function assignRoles(AssignUserRolesRequest $request, User $user): array
    {
        foreach ($request->input('roles') as $role) {
            if ($role === Role::getRootAdmin()->id) {
                continue;
            }

            $user->assignRole($role);
        }

        $response = $this->fractal->item($user)
            ->transformWith($this->getTransformer(UserTransformer::class));

        return $response->toArray();
    }

    /**
     * Unassign role from user
     *
     * Removes roles from a user.
     *
     * @return array<array-key, mixed>
     */
    public function removeRoles(AssignUserRolesRequest $request, User $user): array
    {
        foreach ($request->input('roles') as $role) {
            if ($role === Role::getRootAdmin()->id) {
                continue;
            }

            $user->removeRole($role);
        }

        $response = $this->fractal->item($user)
            ->transformWith($this->getTransformer(UserTransformer::class));

        return $response->toArray();
    }

    /**
     * Create user
     *
     * Store a new user on the system. Returns the created user and an HTTP/201
     * header on successful creation.
     *
     * @throws \Exception
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->creationService->handle($request->validated());

        return $this->fractal->item($user)
            ->transformWith($this->getTransformer(UserTransformer::class))
            ->addMeta([
                'resource' => route('api.application.users.view', [
                    'user' => $user->id,
                ]),
            ])
            ->respond(201);
    }

    /**
     * Delete user
     *
     * Handle a request to delete a user from the Panel. Returns a HTTP/204 response on successful deletion.
     */
    public function delete(DeleteUserRequest $request, User $user): JsonResponse
    {
        $user->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
