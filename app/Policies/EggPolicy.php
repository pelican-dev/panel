<?php

namespace App\Policies;

class EggPolicy
{
    use DefaultAdminPolicies;

    protected string $modelName = 'egg';
}
