<?php

namespace App\Transformers\Api\Application;

use Illuminate\Support\Arr;
use App\Models\Egg;
use App\Models\Server;
use App\Models\EggVariable;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\NullResource;
use App\Services\Acl\Api\AdminAcl;

class EggTransformer extends BaseTransformer
{
    /**
     * Relationships that can be loaded onto this transformation.
     */
    protected array $availableIncludes = [
        'servers',
        'config',
        'script',
        'variables',
    ];

    /**
     * Return the resource name for the JSONAPI output.
     */
    public function getResourceName(): string
    {
        return Egg::RESOURCE_NAME;
    }

    /**
     * Transform an Egg model into a representation that can be consumed by
     * the application api.
     *
     * @throws \JsonException
     */
    public function transform(Egg $model): array
    {
        $model->loadMissing('configFrom');

        $files = json_decode($model->inherit_config_files, true, 512, JSON_THROW_ON_ERROR);

        $model->loadMissing('scriptFrom');

        return [
            'id' => $model->id,
            'uuid' => $model->uuid,
            'name' => $model->name,
            'author' => $model->author,
            'description' => $model->description,
            // "docker_image" is deprecated, but left here to avoid breaking too many things at once
            // in external software. We'll remove it down the road once things have gotten the chance
            // to upgrade to using "docker_images".
            'docker_image' => count($model->docker_images) > 0 ? Arr::first($model->docker_images) : '',
            'docker_images' => $model->docker_images,
            'config' => [
                'files' => $files,
                'startup' => json_decode($model->inherit_config_startup, true),
                'stop' => $model->inherit_config_stop,
                'logs' => json_decode($model->inherit_config_logs, true),
                'file_denylist' => $model->inherit_file_denylist,
                'extends' => $model->config_from,
            ],
            'startup' => $model->startup,
            'script' => [
                'privileged' => $model->script_is_privileged,
                'install' => $model->copy_script_install,
                'entry' => $model->copy_script_entry,
                'container' => $model->copy_script_container,
                'extends' => $model->copy_script_from,
            ],
            $model->getCreatedAtColumn() => $this->formatTimestamp($model->created_at),
            $model->getUpdatedAtColumn() => $this->formatTimestamp($model->updated_at),
        ];
    }

    /**
     * Include the Servers relationship for the given Egg in the transformation.
     *
     * @throws \App\Exceptions\Transformer\InvalidTransformerLevelException
     */
    public function includeServers(Egg $model): Collection|NullResource
    {
        if (!$this->authorize(AdminAcl::RESOURCE_SERVERS)) {
            return $this->null();
        }

        $model->loadMissing('servers');

        return $this->collection($model->getRelation('servers'), $this->makeTransformer(ServerTransformer::class), Server::RESOURCE_NAME);
    }

    /**
     * Include the variables that are defined for this Egg.
     *
     * @throws \App\Exceptions\Transformer\InvalidTransformerLevelException
     */
    public function includeVariables(Egg $model): Collection|NullResource
    {
        if (!$this->authorize(AdminAcl::RESOURCE_EGGS)) {
            return $this->null();
        }

        $model->loadMissing('variables');

        return $this->collection(
            $model->getRelation('variables'),
            $this->makeTransformer(EggVariableTransformer::class),
            EggVariable::RESOURCE_NAME
        );
    }
}
