<?php

namespace App\Data\Api\Client;

use App\Data\Api\ApiResource;
use App\Models\Egg;

class EggData extends ApiResource
{
    public function __construct(
        public string $uuid,
        public string $name,
    ) {}

    public static function getResourceName(): string
    {
        return Egg::RESOURCE_NAME;
    }

    public static function fromModel(Egg $model): static
    {
        return new static(
            uuid: $model->uuid,
            name: $model->name,
        );
    }
}
