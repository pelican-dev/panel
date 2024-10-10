<?php

namespace App\Http\Requests\Api\Application\Allocations;

use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\Allocation;

class StoreAllocationRequest extends ApplicationApiRequest
{
    protected ?string $resource = Allocation::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;

    public function rules(): array
    {
        return [
            'ip' => 'required|string',
            'alias' => 'sometimes|nullable|string|max:255',
            'ports' => 'required|array',
            'ports.*' => 'string',
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $data = parent::validated();

        return [
            'allocation_ip' => $data['ip'],
            'allocation_ports' => $data['ports'],
            'allocation_alias' => $data['alias'] ?? null,
        ];
    }
}
