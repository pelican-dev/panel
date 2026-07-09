<?php

namespace App\Http\Requests\Api\Application\DatabaseHosts;

use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\DatabaseHost;
use App\Services\Acl\Api\AdminAcl;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreDatabaseHostRequest extends ApplicationApiRequest
{
    protected ?string $resource = DatabaseHost::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;

    /**
     * @param  array<string, string|array<string|\Stringable|ValidationRule>>|null  $rules
     * @return array<string, string|array<string|\Stringable|ValidationRule>>
     */
    public function rules(?array $rules = null): array
    {
        return $rules ?? DatabaseHost::getRules();
    }
}
