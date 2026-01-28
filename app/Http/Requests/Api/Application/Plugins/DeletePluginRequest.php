<?php

namespace App\Http\Requests\Api\Application\Plugins;

use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\Plugin;
use App\Services\Acl\Api\AdminAcl;

class DeletePluginRequest extends ApplicationApiRequest
{
    protected ?string $resource = Plugin::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;

    /**
     * @param  array<array-key, string|string[]>|null  $rules
     * @return array<array-key, string|string[]>
     */
    public function rules(?array $rules = null): array
    {
        return [
            'delete' => 'boolean',
        ];
    }
}
