<?php

namespace App\Policies;

class ApiKeyPolicy
{
    use DefaultPolicies;

    protected string $modelName = 'apikey';
}
