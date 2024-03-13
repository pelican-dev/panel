<?php

namespace App\Services\Locations;

use App\Models\Location;
use App\Contracts\Repository\LocationRepositoryInterface;

class LocationCreationService
{
    /**
     * LocationCreationService constructor.
     */
    public function __construct(protected LocationRepositoryInterface $repository)
    {
    }

    /**
     * Create a new location.
     *
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function handle(array $data): Location
    {
        return $this->repository->create($data);
    }
}
