<?php

namespace App\Policies;

class ApiKeyPolicy
{
    use DefaultAdminPolicies;

    protected string $modelName = 'apiKey';
}
