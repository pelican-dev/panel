<?php

namespace App\Policies;

use App\Models\Server;
use App\Models\User;

class ServerPolicy
{
    use DefaultPolicies;

    protected string $modelName = 'server';

    /**
     * Runs before any of the functions are called. Used to determine if the (sub-)user has permissions.
     */
    public function before(User $user, string $ability, Server $server): bool
    {
        if ($server->owner_id === $user->id) {
            return true;
        }

        $subuser = $server->subusers->where('user_id', $user->id)->first();
        if (!$subuser || empty($ability)) {
            return false;
        }

        return in_array($ability, $subuser->permissions);
    }

    /**
     * This is a horrendous hack to avoid Laravel's "smart" behavior that does
     * not call the before() function if there isn't a function matching the
     * policy permission.
     */
    public function __call(string $name, mixed $arguments)
    {
        // do nothing
    }
}
