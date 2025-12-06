<?php

namespace App\Policies\Admin;

class ApiKeyPolicy
{
    use DefaultPolicies;

    protected string $modelName = 'apiKey';
}
