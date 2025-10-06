<?php

namespace App\Transformers\Api\Application;

use App\Models\Database;
use App\Models\DatabaseHost;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\NullResource;

class ServerDatabaseTransformer extends BaseTransformer
{
    protected array $availableIncludes = ['password', 'host'];

    /**
     * Return the resource name for the JSONAPI output.
     */
    public function getResourceName(): string
    {
        return Database::RESOURCE_NAME;
    }

    /**
     * @param  Database  $model
     */
    public function transform($model): array
    {
        return [
            'id' => $model->id,
            'server' => $model->server_id,
            'host' => $model->database_host_id,
            'database' => $model->database,
            'username' => $model->username,
            'remote' => $model->remote,
            'max_connections' => $model->max_connections,
            'created_at' => $model->created_at->toAtomString(),
            'updated_at' => $model->updated_at->toAtomString(),
        ];
    }

    /**
     * Include the database password in the request.
     */
    public function includePassword(Database $model): Item
    {
        return $this->item($model, function (Database $model) {
            return [
                'password' => $model->password,
            ];
        }, 'database_password');
    }

    /**
     * Return the database host relationship for this server database.
     */
    public function includeHost(Database $model): Item|NullResource
    {
        if (!$this->authorize(DatabaseHost::RESOURCE_NAME)) {
            return $this->null();
        }

        $model->loadMissing('host');

        return $this->item(
            $model->getRelation('host'),
            $this->makeTransformer(DatabaseHostTransformer::class),
            DatabaseHost::RESOURCE_NAME
        );
    }
}
