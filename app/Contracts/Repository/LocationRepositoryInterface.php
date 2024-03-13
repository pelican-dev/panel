<?php

namespace App\Contracts\Repository;

use App\Models\Location;
use Illuminate\Support\Collection;

interface LocationRepositoryInterface extends RepositoryInterface
{
    /**
     * Return locations with a count of nodes and servers attached to it.
     */
    public function getAllWithDetails(): Collection;

    /**
     * Return all the available locations with the nodes as a relationship.
     */
    public function getAllWithNodes(): Collection;

    /**
     * Return all the nodes and their respective count of servers for a location.
     *
     * @throws \App\Exceptions\Repository\RecordNotFoundException
     */
    public function getWithNodes(int $id): Location;

    /**
     * Return a location and the count of nodes in that location.
     *
     * @throws \App\Exceptions\Repository\RecordNotFoundException
     */
    public function getWithNodeCount(int $id): Location;
}
