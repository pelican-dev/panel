<?php

namespace App\Policies;

class RolePolicy
{
    use DefaultPolicies;

    protected string $modelName = 'role';
}
