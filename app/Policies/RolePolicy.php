<?php

namespace App\Policies;

class RolePolicy
{
    use DefaultAdminPolicies;

    protected string $modelName = 'role';
}
