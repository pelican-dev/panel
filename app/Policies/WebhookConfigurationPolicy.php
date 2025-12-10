<?php

namespace App\Policies;

class WebhookConfigurationPolicy
{
    use DefaultAdminPolicies;

    protected string $modelName = 'webhook';
}
