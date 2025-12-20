<?php

namespace App\Policies;

class PluginPolicy
{
    use DefaultAdminPolicies;

    protected string $modelName = 'plugin';
}
