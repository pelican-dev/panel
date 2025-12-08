<?php

namespace App\Policies\Admin;

class WebhookConfigurationPolicy
{
    use DefaultPolicies;

    protected string $modelName = 'webhook';
}
