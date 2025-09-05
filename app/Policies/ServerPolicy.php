<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\Server;
use App\Models\User;

class ServerPolicy
{
    use DefaultPolicies;

    protected string $modelName = 'server';

    /**
     * Runs before any of the functions are called. Used to determine if the (sub-)user has permissions.
     */
    public function before(User $user, string $ability, string|Server $server): ?bool
    {
        // For "viewAny" the $server param is the class name
        if (is_string($server)) {
            return null;
        }

        if (Permission::permissionKeys()->contains($ability)) {
            // Owner has full server permissions
            if ($server->owner_id === $user->id) {
                return true;
            }

            $subuser = $server->subusers->where('user_id', $user->id)->first();
            // If the user is a subuser check their permissions
            if ($subuser && in_array($ability, $subuser->permissions)) {
                return true;
            }
        }

        // Make sure user can target node of the server
        if (!$user->canTarget($server->node)) {
            return false;
        }

        // Return null to let default policies take over
        return null;
    }

    /**
     * This is a horrendous hack to avoid Laravel's "smart" behavior that does
     * not call the before() function if there isn't a function matching the
     * policy permission.
     */
    public function __call(string $name, mixed $arguments): void
    {
        // do nothing
    }
}
