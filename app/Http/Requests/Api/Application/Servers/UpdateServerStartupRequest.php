<?php

namespace App\Http\Requests\Api\Application\Servers;

use App\Models\Server;
use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class UpdateServerStartupRequest extends ApplicationApiRequest
{
    protected ?string $resource = Server::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;

    /**
     * Validation rules to run the input against.
     */
    public function rules(): array
    {
        $data = Server::getRulesForUpdate($this->parameter('server', Server::class));

        return [
            'startup' => 'sometimes|string',
            'environment' => 'present|array',
            'egg' => $data['egg_id'],
            'image' => 'sometimes|string',
            'skip_scripts' => 'present|boolean',
        ];
    }

    /**
     * Return the validated data in a format that is expected by the service.
     *
     * @return array<string, mixed>
     */
    public function validated($key = null, $default = null): array
    {
        $data = parent::validated();

        return collect($data)->only(['startup', 'environment', 'skip_scripts'])->merge([
            'egg_id' => array_get($data, 'egg'),
            'docker_image' => array_get($data, 'image'),
        ])->toArray();
    }
}
