<?php

namespace App\Policies;

class UserPolicy
{
    use DefaultPolicies;

    protected string $modelName = 'user';
}
