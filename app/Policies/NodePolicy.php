<?php

namespace App\Policies;

use App\Models\Node;
use App\Models\User;

class NodePolicy
{
    use DefaultAdminPolicies;

    protected string $modelName = 'node';

    public function before(User $user, string $ability, string|Node $node): ?bool
    {
        // For "viewAny" the $node param is the class name
        if (is_string($node)) {
            return null;
        }

        if (!$user->canTarget($node)) {
            return false;
        }

        return null;
    }
}
