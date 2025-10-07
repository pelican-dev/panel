<?php

namespace App\Http\Requests\Api\Application\Servers;

use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\Objects\DeploymentObject;
use App\Models\Server;
use App\Services\Acl\Api\AdminAcl;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreServerRequest extends ApplicationApiRequest
{
    protected ?string $resource = Server::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;

    /**
     * Rules to be applied to this request.
     */
    public function rules(): array
    {
        $rules = Server::getRules();

        return [
            'external_id' => $rules['external_id'],
            'name' => $rules['name'],
            'description' => array_merge(['nullable'], $rules['description']),
            'user' => $rules['owner_id'],
            'egg' => $rules['egg_id'],
            'docker_image' => 'sometimes|string',
            'startup' => 'sometimes|string',
            'environment' => 'present|array',
            'skip_scripts' => 'sometimes|boolean',
            'oom_killer' => 'sometimes|boolean',

            // Resource limitations
            'limits' => 'required|array',
            'limits.memory' => $rules['memory'],
            'limits.swap' => $rules['swap'],
            'limits.disk' => $rules['disk'],
            'limits.io' => $rules['io'],
            'limits.threads' => $rules['threads'],
            'limits.cpu' => $rules['cpu'],

            // Application Resource Limits
            'feature_limits' => 'required|array',
            'feature_limits.databases' => $rules['database_limit'],
            'feature_limits.allocations' => $rules['allocation_limit'],
            'feature_limits.backups' => $rules['backup_limit'],

            // Placeholders for rules added in withValidator() function.
            'allocation.default' => '',
            'allocation.additional.*' => '',

            // Automatic deployment rules
            'deploy' => 'sometimes|required|array',
            // Locations are deprecated, use tags
            'deploy.locations' => 'sometimes|array',
            'deploy.locations.*' => 'required_with:deploy.locations|integer|min:1',
            'deploy.tags' => 'array',
            'deploy.tags.*' => 'required_with:deploy.tags|string|min:1',
            'deploy.dedicated_ip' => 'required_with:deploy|boolean',
            'deploy.port_range' => 'array',
            'deploy.port_range.*' => 'string',
            'start_on_completion' => 'sometimes|boolean',
        ];
    }

    /**
     * Normalize the data into a format that can be consumed by the service.
     *
     * @return array{
     *     external_id: int,
     *     name: string,
     *     description: string,
     *     owner_id: int,
     *     egg_id: int,
     *     image: string,
     *     startup: string,
     *     environment: string,
     *     memory: int,
     *     swap: int,
     *     disk: int,
     *     io: int,
     *     cpu: int,
     *     threads: int,
     *     skip_scripts: bool,
     *     allocation_id: int,
     *     allocation_additional: int[],
     *     start_on_completion: bool,
     *     database_limit: int,
     *     allocation_limit: int,
     *     backup_limit: int,
     *     oom_killer: bool,
     * }
     */
    public function validated($key = null, $default = null): array
    {
        $data = parent::validated();

        return [
            'external_id' => array_get($data, 'external_id'),
            'name' => array_get($data, 'name'),
            'description' => array_get($data, 'description'),
            'owner_id' => array_get($data, 'user'),
            'egg_id' => array_get($data, 'egg'),
            'image' => array_get($data, 'docker_image'),
            'startup' => array_get($data, 'startup'),
            'environment' => array_get($data, 'environment'),
            'memory' => array_get($data, 'limits.memory'),
            'swap' => array_get($data, 'limits.swap'),
            'disk' => array_get($data, 'limits.disk'),
            'io' => array_get($data, 'limits.io'),
            'cpu' => array_get($data, 'limits.cpu'),
            'threads' => array_get($data, 'limits.threads'),
            'skip_scripts' => array_get($data, 'skip_scripts', false),
            'allocation_id' => array_get($data, 'allocation.default'),
            'allocation_additional' => array_get($data, 'allocation.additional'),
            'start_on_completion' => array_get($data, 'start_on_completion', false),
            'database_limit' => array_get($data, 'feature_limits.databases'),
            'allocation_limit' => array_get($data, 'feature_limits.allocations'),
            'backup_limit' => array_get($data, 'feature_limits.backups'),
            'oom_killer' => array_get($data, 'oom_killer'),
        ];
    }

    /*
     * Run validation after the rules above have been applied.
     *
     * @param \Illuminate\Validation\Validator $validator
     */
    public function withValidator(Validator $validator): void
    {
        $validator->sometimes('allocation.default', [
            'required', 'integer', 'bail',
            Rule::exists('allocations', 'id')->where(function ($query) {
                $query->whereNull('server_id');
            }),
        ], function ($input) {
            return !$input->deploy;
        });

        $validator->sometimes('allocation.additional.*', [
            'integer',
            Rule::exists('allocations', 'id')->where(function ($query) {
                $query->whereNull('server_id');
            }),
        ], function ($input) {
            return !$input->deploy;
        });

        /** @deprecated use tags instead */
        $validator->sometimes('deploy.locations', 'present', function ($input) {
            return $input->deploy;
        });

        $validator->sometimes('deploy.tags', 'present', function ($input) {
            return $input->deploy;
        });

        $validator->sometimes('deploy.port_range', 'present', function ($input) {
            return $input->deploy;
        });
    }

    /**
     * Return a deployment object that can be passed to the server creation service.
     */
    public function getDeploymentObject(): ?DeploymentObject
    {
        if (is_null($this->input('deploy'))) {
            return null;
        }

        $object = new DeploymentObject();
        $object->setDedicated($this->input('deploy.dedicated_ip', false));
        $object->setTags($this->input('deploy.tags', $this->input('deploy.locations', [])));
        $object->setPorts($this->input('deploy.port_range', []));

        return $object;
    }
}
