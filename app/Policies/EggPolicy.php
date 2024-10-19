<?php

namespace App\Policies;

class EggPolicy
{
    use DefaultPolicies;

    protected string $modelName = 'egg';
}
