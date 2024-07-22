<?php

namespace App\Policies;

class ServerPolicy
{
    use DefaultPolicies;

    protected string $modelName = 'server';
}
