<?php

namespace App\Extensions\Tasks;

class TaskService
{
    /** @var array<string, TaskSchemaInterface> */
    private array $schemas = [];

    /**
     * @return TaskSchemaInterface[]
     */
    public function getAll(): array
    {
        return $this->schemas;
    }

    public function get(string $id): ?TaskSchemaInterface
    {
        return array_get($this->schemas, $id);
    }

    public function register(TaskSchemaInterface $schema): void
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
