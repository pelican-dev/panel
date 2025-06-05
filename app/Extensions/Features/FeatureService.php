<?php

namespace App\Extensions\Features;

use App\Models\Server;

class FeatureService
{
    /** @var FeatureSchemaInterface[] */
    private array $schemas = [];

    /**
     * @param  string[]|string|null  $id
     * @return FeatureSchemaInterface[] | FeatureSchemaInterface
     */
    public function get(array|string|null $id = null): array|FeatureSchemaInterface
    {
        if (is_array($id)) {
            return collect($this->schemas)->only($id)->all();
        }

        return $id ? $this->schemas[$id] : $this->schemas;
    }

    public function register(FeatureSchemaInterface $schema): void
    {
        if (array_key_exists($schema->getId(), $this->schemas)) {
            return;
        }

        $this->schemas[$schema->getId()] = $schema;
    }

    /** @return array<string, array<string>> */
    public function getMappings(Server $server): array
    {
        return collect($this->get($server->egg->features))->mapWithKeys(fn (FeatureSchemaInterface $schema) => [
            $schema->getId() => $schema->getListeners(),
        ])->all();
    }
}
