<?php

namespace App\Policies;

class DatabasePolicy
{
    use DefaultPolicies;

    protected string $modelName = 'database';
}
