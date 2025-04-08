<?php

namespace App\Policies;

use App\Models\Node;
use App\Models\User;

class NodePolicy
{
    use DefaultPolicies;

    protected string $modelName = 'node';

    public function before(User $user, string $ability, string|Node $node): ?bool
    {
        // For "viewAny" the $server param is the class name
        if (is_string($node)) {
            return null;
        }

        return $user->canTarget($node);
    }
}
