<?php

namespace App\Policies;

use App\Models\Mount;
use App\Models\User;

class MountPolicy
{
    use DefaultAdminPolicies;

    protected string $modelName = 'mount';

    public function before(User $user, string $ability, string|Mount $mount): ?bool
    {
        // For "viewAny" the $mount param is the class name
        if (is_string($mount)) {
            return null;
        }

        foreach ($mount->nodes as $node) {
            if (!$user->canTarget($node)) {
                return false;
            }
        }

        return null;
    }
}
