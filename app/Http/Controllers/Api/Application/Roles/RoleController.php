<?php

namespace App\Http\Controllers\Api\Application\Roles;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Models\Role;
use Spatie\QueryBuilder\QueryBuilder;
use App\Transformers\Api\Application\RoleTransformer;
use App\Http\Controllers\Api\Application\ApplicationApiController;
use App\Http\Requests\Api\Application\Roles\GetRoleRequest;
use App\Http\Requests\Api\Application\Roles\StoreRoleRequest;
use App\Http\Requests\Api\Application\Roles\DeleteRoleRequest;
use App\Http\Requests\Api\Application\Roles\UpdateRoleRequest;

class RoleController extends ApplicationApiController
{
    /**
     * Return all the roles currently registered on the Panel.
     */
    public function index(GetRoleRequest $request): array
    {
        $roles = QueryBuilder::for(Role::query())
            ->allowedFilters(['name'])
            ->allowedSorts(['name'])
            ->paginate($request->query('per_page') ?? 10);

        return $this->fractal->collection($roles)
            ->transformWith($this->getTransformer(RoleTransformer::class))
            ->toArray();
    }

    /**
     * Return a single role.
     */
    public function view(GetRoleRequest $request, Role $role): array
    {
        return $this->fractal->item($role)
            ->transformWith($this->getTransformer(RoleTransformer::class))
            ->toArray();
    }

    /**
     * Store a new role on the Panel and return an HTTP/201 response code with the
     * new role attached.
     *
     * @throws \Throwable
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = Role::create($request->validated());

        return $this->fractal->item($role)
            ->transformWith($this->getTransformer(RoleTransformer::class))
            ->addMeta([
                'resource' => route('api.application.roles.view', [
                    'role' => $role->id,
                ]),
            ])
            ->respond(201);
    }

    /**
     * Update a role on the Panel and return the updated record to the user.
     *
     * @throws \Throwable
     */
    public function update(UpdateRoleRequest $request, Role $role): array
    {
        $role->update($request->validated());

        return $this->fractal->item($role)
            ->transformWith($this->getTransformer(RoleTransformer::class))
            ->toArray();
    }

    /**
     * Delete a role from the Panel.
     *
     * @throws \Exception
     */
    public function delete(DeleteRoleRequest $request, Role $role): Response
    {
        $role->delete();

        return $this->returnNoContent();
    }
}
