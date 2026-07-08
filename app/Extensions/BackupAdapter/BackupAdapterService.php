<?php

namespace App\Extensions\BackupAdapter;

class BackupAdapterService
{
    /** @var array<string, BackupAdapterSchemaInterface> */
    private array $schemas = [];

    /** @return BackupAdapterSchemaInterface[] */
    public function getAll(): array
    {
        return $this->schemas;
    }

    public function get(string $id): ?BackupAdapterSchemaInterface
    {
        return array_get($this->schemas, $id);
    }

    public function register(BackupAdapterSchemaInterface $schema): void
    {
        if (array_key_exists($schema->getId(), $this->schemas)) {
            return;
        }

        $this->schemas[$schema->getId()] = $schema;
    }

    /** @return array<string, string> */
    public function getMappings(): array
    {
        return collect($this->schemas)->mapWithKeys(fn ($schema) => [$schema->getId() => $schema->getName()])->all();
    }
}
