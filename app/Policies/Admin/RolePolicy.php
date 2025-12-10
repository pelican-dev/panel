<?php

namespace App\Policies\Admin;

class RolePolicy
{
    use DefaultPolicies;

    protected string $modelName = 'role';
}
