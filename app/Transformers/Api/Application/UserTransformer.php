<?php

namespace App\Transformers\Api\Application;

use App\Models\Role;
use App\Models\User;
use App\Models\Server;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\NullResource;

class UserTransformer extends BaseTransformer
{
    /**
     * List of resources that can be included.
     */
    protected array $availableIncludes = [
        'servers',
        'roles',
    ];

    /**
     * Return the resource name for the JSONAPI output.
     */
    public function getResourceName(): string
    {
        return User::RESOURCE_NAME;
    }

    /**
     * Return a transformed User model that can be consumed by external services.
     */
    public function transform(User $user): array
    {
        return [
            'id' => $user->id,
            'external_id' => $user->external_id,
            'uuid' => $user->uuid,
            'username' => $user->username,
            'email' => $user->email,
            'first_name' => $user->name_first,
            'last_name' => $user->name_last,
            'language' => $user->language,
            'root_admin' => $user->isRootAdmin(),
            '2fa_enabled' => (bool) $user->use_totp,
            '2fa' => (bool) $user->use_totp, // deprecated, use "2fa_enabled"
            'created_at' => $this->formatTimestamp($user->created_at),
            'updated_at' => $this->formatTimestamp($user->updated_at),
        ];
    }

    /**
     * Return the servers associated with this user.
     */
    public function includeServers(User $user): Collection|NullResource
    {
        if (!$this->authorize(Server::RESOURCE_NAME)) {
            return $this->null();
        }

        $user->loadMissing('servers');

        return $this->collection($user->getRelation('servers'), $this->makeTransformer(ServerTransformer::class), 'server');
    }

    /**
     * Return the roles associated with this user.
     */
    public function includeRoles(User $user): Collection|NullResource
    {
        if (!$this->authorize(Role::RESOURCE_NAME)) {
            return $this->null();
        }

        $user->loadMissing('roles');

        return $this->collection($user->getRelation('roles'), $this->makeTransformer(RoleTransformer::class), Role::RESOURCE_NAME);
    }
}
