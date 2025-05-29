<?php

namespace App\Services\Servers;

use App\Models\User;
use App\Models\Server;

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
        if ($user->isAdmin() && ($user->can('view', $server) || $user->can('update', $server))) {
            $permissions = $user->can('update', $server) ? ['*'] : ['websocket.connect', 'backup.read'];

            $permissions[] = 'admin.websocket.errors';
            $permissions[] = 'admin.websocket.install';
            $permissions[] = 'admin.websocket.transfer';

            return $permissions;
        }

        if ($user->id === $server->owner_id) {
            return ['*'];
        }

        /** @var \App\Models\Subuser|null $subuserPermissions */
        $subuserPermissions = $server->subusers()->where('user_id', $user->id)->first();

        return $subuserPermissions ? $subuserPermissions->permissions : [];
    }
}
