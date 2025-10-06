<?php

namespace App\Http\Requests\Api\Application\Allocations;

use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\Allocation;
use App\Services\Acl\Api\AdminAcl;

class StoreAllocationRequest extends ApplicationApiRequest
{
    protected ?string $resource = Allocation::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;

    /** @return array<string, string|string[]> */
    public function rules(): array
    {
        return [
            'ip' => 'required|string',
            'alias' => 'sometimes|nullable|string|max:255',
            'ports' => 'required|array',
            'ports.*' => 'string',
        ];
    }

    /**
     * @return array<string, mixed>
     */
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
