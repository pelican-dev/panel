<?php

namespace App\Policies;

use App\Models\DatabaseHost;
use App\Models\User;

class DatabaseHostPolicy
{
    use DefaultPolicies;

    protected string $modelName = 'databasehost';

    public function before(User $user, string $ability, string|DatabaseHost $databaseHost): ?bool
    {
        // For "viewAny" the $databaseHost param is the class name
        if (is_string($databaseHost)) {
            return null;
        }

        foreach ($databaseHost->nodes as $node) {
            if (!$user->canTarget($node)) {
                return false;
            }
        }

        return null;
    }
}
