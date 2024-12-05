<?php

namespace App\Policies;

class WebhookConfigurationPolicy
{
    use DefaultPolicies;

    protected string $modelName = 'webhook';
}
