<?php

namespace App\Extensions\Features;

class FeatureService
{
    /** @var FeatureSchemaInterface[] */
    private array $schemas = [];

    /**
     * @return FeatureSchemaInterface[]
     */
    public function getAll(): array
    {
        return $this->schemas;
    }

    public function get(string $id): ?FeatureSchemaInterface
    {
        return array_get($this->schemas, $id);
    }

    /**
     * @param  ?string[]  $features
     * @return FeatureSchemaInterface[]
     */
    public function getActiveSchemas(?array $features = []): array
    {
        return collect($this->schemas)->only($features)->all();
    }

    public function register(FeatureSchemaInterface $schema): void
    {
        if (array_key_exists($schema->getId(), $this->schemas)) {
            return;
        }

        $this->schemas[$schema->getId()] = $schema;
    }

    /**
     * @param  ?string[]  $features
     * @return array<string, array<string>>
     */
    public function getMappings(?array $features = []): array
    {
        return collect($this->getActiveSchemas($features))
            ->mapWithKeys(fn (FeatureSchemaInterface $schema) => [
                $schema->getId() => $schema->getListeners(),
            ])->all();
    }
}
