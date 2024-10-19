<?php

namespace App\Policies;

class DatabaseHostPolicy
{
    use DefaultPolicies;

    protected string $modelName = 'databasehost';
}
