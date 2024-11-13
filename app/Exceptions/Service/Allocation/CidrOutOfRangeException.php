<?php

namespace App\Exceptions\Service\Allocation;

use App\Exceptions\DisplayException;
use App\Services\Allocations\AssignmentService;

class CidrOutOfRangeException extends DisplayException
{
    /**
     * CidrOutOfRangeException constructor.
     */
    public function __construct(int $min = AssignmentService::IPV4_CIDR_MIN_BITS, int $max = AssignmentService::IPV4_CIDR_MAX_BITS, int $version = 4)
    {
        parent::__construct(trans('exceptions.allocations.cidr_out_of_range', compact('min', 'max', 'version')));
    }
}
