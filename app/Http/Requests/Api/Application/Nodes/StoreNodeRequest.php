<?php

namespace App\Http\Requests\Api\Application\Nodes;

use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\Node;
use App\Services\Acl\Api\AdminAcl;

class StoreNodeRequest extends ApplicationApiRequest
{
    protected ?string $resource = Node::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;

    /**
     * @param  array<string, string|string[]>|null  $rules
     * @return array<string, string|string[]>
     */
    public function rules(?array $rules = null): array
    {
        return collect($rules ?? Node::getRules())->mapWithKeys(function ($value, $key) {
            return [snake_case($key) => $value];
        })->toArray();
    }

    /**
     * Fields to rename for clarity in the API response.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'daemon_base' => 'Daemon Base Path',
            'upload_size' => 'File Upload Size Limit',
            'public' => 'Node Visibility',
        ];
    }

    /**
     * Change the formatting of some data keys in the validated response data
     * to match what the application expects in the services.
     *
     * @return array<string, mixed>
     */
    public function validated($key = null, $default = null): array
    {
        $response = parent::validated();
        $response['daemon_base'] = $response['daemon_base'] ?? (new Node())->getAttribute('daemon_base');

        return $response;
    }
}
