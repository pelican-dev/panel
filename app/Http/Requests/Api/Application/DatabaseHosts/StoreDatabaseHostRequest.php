<?php

namespace App\Http\Requests\Api\Application\DatabaseHosts;

use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\DatabaseHost;
use App\Services\Acl\Api\AdminAcl;

class StoreDatabaseHostRequest extends ApplicationApiRequest
{
    protected ?string $resource = DatabaseHost::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;

    /**
     * @param  array<string, string|string[]>|null  $rules
     * @return array<string, string|string[]>
     */
    public function rules(?array $rules = null): array
    {
        return $rules ?? DatabaseHost::getRules();
    }
}
