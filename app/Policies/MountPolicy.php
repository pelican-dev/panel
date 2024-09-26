<?php

namespace App\Policies;

class MountPolicy
{
    use DefaultPolicies;

    protected string $modelName = 'mount';
}
