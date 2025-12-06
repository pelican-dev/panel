<?php

namespace App\Services\Servers;

use App\Enums\SubuserPermission;
use App\Models\Server;
use App\Models\Subuser;
use App\Models\User;

class GetUserPermissionsService
{
    /**
     * Returns the server specific permissions that a user has. This checks
     * if they are an admin, the owner or a subuser for the server. If no
     * permissions are found, an empty array is returned.
     *
     * @return string[]
     */
    public function handle(Server $server, User $user): array
    {
        $isOwner = $user->id === $server->owner_id;
        $isAdmin = $user->isAdmin() && ($user->can('view', $server) || $user->can('update', $server));

        if ($isOwner && !$isAdmin) {
            return ['*'];
        }

        $adminPermissions = [
            'admin.websocket.errors',
            'admin.websocket.install',
            'admin.websocket.transfer',
        ];

        if ($isAdmin) {
            return $isOwner || $user->can('update', $server) ? array_merge(['*'], $adminPermissions) : array_merge([SubuserPermission::WebsocketConnect->value], $adminPermissions);
        }

        /** @var Subuser|null $subuser */
        $subuser = $server->subusers()->where('user_id', $user->id)->first();

        return $subuser->permissions ?? [];
    }
}
