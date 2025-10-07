<?php

namespace App\Http\Controllers\Api\Application\Roles;

use App\Exceptions\PanelException;
use App\Http\Controllers\Api\Application\ApplicationApiController;
use App\Http\Requests\Api\Application\Roles\DeleteRoleRequest;
use App\Http\Requests\Api\Application\Roles\GetRoleRequest;
use App\Http\Requests\Api\Application\Roles\StoreRoleRequest;
use App\Http\Requests\Api\Application\Roles\UpdateRoleRequest;
use App\Models\Role;
use App\Transformers\Api\Application\RoleTransformer;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class RoleController extends ApplicationApiController
{
    /**
     * List roles
     *
     * Return all the roles currently registered on the Panel.
     *
     * @return array<array-key, mixed>
     */
    public function index(GetRoleRequest $request): array
    {
        $roles = QueryBuilder::for(Role::class)
            ->allowedFilters(['id', 'name'])
            ->allowedSorts(['id', 'name'])
            ->paginate($request->query('per_page') ?? 10);

        return $this->fractal->collection($roles)
            ->transformWith($this->getTransformer(RoleTransformer::class))
            ->toArray();
    }

    /**
     * View role
     *
     * Return a single role.
     *
     * @return array<array-key, mixed>
     */
    public function view(GetRoleRequest $request, Role $role): array
    {
        return $this->fractal->item($role)
            ->transformWith($this->getTransformer(RoleTransformer::class))
            ->toArray();
    }

    /**
     * Create role
     *
     * Store a new role on the Panel and return an HTTP/201 response code with the
     * new role attached.
     *
     * @throws Throwable
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
     * Update role
     *
     * Update a role on the Panel and return the updated record to the user.
     *
     * @return array<array-key, mixed>
     *
     * @throws Throwable
     */
    public function update(UpdateRoleRequest $request, Role $role): array
    {
        if ($role->isRootAdmin()) {
            throw new PanelException('Can\'t update root admin role!');
        }

        $role->update($request->validated());

        return $this->fractal->item($role)
            ->transformWith($this->getTransformer(RoleTransformer::class))
            ->toArray();
    }

    /**
     * Delete role
     *
     * Delete a role from the Panel.
     *
     * @throws Exception
     */
    public function delete(DeleteRoleRequest $request, Role $role): Response
    {
        if ($role->isRootAdmin()) {
            throw new PanelException('Can\'t delete root admin role!');
        }

        $role->delete();

        return $this->returnNoContent();
    }
}
