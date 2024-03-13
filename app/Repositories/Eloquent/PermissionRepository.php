<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repository\PermissionRepositoryInterface;

class PermissionRepository extends EloquentRepository implements PermissionRepositoryInterface
{
    /**
     * Return the model backing this repository.
     *
     * @throws \Exception
     */
    public function model(): string
    {
        throw new \Exception('This functionality is not implemented.');
    }
}
