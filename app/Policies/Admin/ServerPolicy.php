<?php

namespace App\Policies\Admin;

class ServerPolicy
{
    use DefaultPolicies;

    protected string $modelName = 'server';
}
