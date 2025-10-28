<?php

namespace App\Policies\Admin;

class EggPolicy
{
    use DefaultPolicies;

    protected string $modelName = 'egg';
}
