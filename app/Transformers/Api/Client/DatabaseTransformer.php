<?php

namespace App\Transformers\Api\Client;

use App\Enums\SubuserPermission;
use App\Models\Database;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\NullResource;

class DatabaseTransformer extends BaseClientTransformer
{
    protected array $availableIncludes = ['password'];

    public function getResourceName(): string
    {
        return Database::RESOURCE_NAME;
    }

    /**
     * @param  Database  $model
     */
    public function transform($model): array
    {
        $model->loadMissing('host');

        return [
            'id' => $model->id,
            'host' => [
                'address' => $model->getRelation('host')->host,
                'port' => $model->getRelation('host')->port,
            ],
            'name' => $model->database,
            'username' => $model->username,
            'connections_from' => $model->remote,
            'max_connections' => $model->max_connections,
        ];
    }

    /**
     * Include the database password in the request.
     */
    public function includePassword(Database $database): Item|NullResource
    {
        if (!$this->request->user()->can(SubuserPermission::DatabaseViewPassword, $database->server)) {
            return $this->null();
        }

        return $this->item($database, function (Database $model) {
            return [
                'password' => $model->password,
            ];
        }, 'database_password');
    }
}
