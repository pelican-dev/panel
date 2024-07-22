<?php

namespace App\Policies;

class NodePolicy
{
    use DefaultPolicies;

    protected string $modelName = 'node';
}
