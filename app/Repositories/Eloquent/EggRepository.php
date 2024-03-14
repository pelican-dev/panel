<?php

namespace App\Repositories\Eloquent;

use App\Models\Egg;
use Webmozart\Assert\Assert;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Contracts\Repository\EggRepositoryInterface;
use App\Exceptions\Repository\RecordNotFoundException;

class EggRepository extends EloquentRepository implements EggRepositoryInterface
{
    /**
     * Return the model backing this repository.
     */
    public function model(): string
    {
        return Egg::class;
    }

    /**
     * Return an egg with the variables relation attached.
     *
     * @throws \App\Exceptions\Repository\RecordNotFoundException
     */
    public function getWithVariables(int $id): Egg
    {
        try {
            return $this->getBuilder()->with('variables')->findOrFail($id, $this->getColumns());
        } catch (ModelNotFoundException) {
            throw new RecordNotFoundException();
        }
    }

    /**
     * Return all eggs and their relations to be used in the daemon API.
     */
    public function getAllWithCopyAttributes(): Collection
    {
        return $this->getBuilder()->with('scriptFrom', 'configFrom')->get($this->getColumns());
    }

    /**
     * Return an egg with the scriptFrom and configFrom relations loaded onto the model.
     *
     * @param int|string $value
     *
     * @throws \App\Exceptions\Repository\RecordNotFoundException
     */
    public function getWithCopyAttributes($value, string $column = 'id'): Egg
    {
        Assert::true(is_digit($value) || is_string($value), 'First argument passed to getWithCopyAttributes must be an integer or string, received %s.');

        try {
            return $this->getBuilder()->with('scriptFrom', 'configFrom')->where($column, '=', $value)->firstOrFail($this->getColumns());
        } catch (ModelNotFoundException) {
            throw new RecordNotFoundException();
        }
    }

    /**
     * Return all the data needed to export a service.
     *
     * @throws \App\Exceptions\Repository\RecordNotFoundException
     */
    public function getWithExportAttributes(int $id): Egg
    {
        try {
            return $this->getBuilder()->with('scriptFrom', 'configFrom', 'variables')->findOrFail($id, $this->getColumns());
        } catch (ModelNotFoundException) {
            throw new RecordNotFoundException();
        }
    }
}
