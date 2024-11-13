<?php

namespace App\Exceptions\Service\Allocation;

use App\Exceptions\DisplayException;

class PortOutOfRangeException extends DisplayException
{
    /**
     * PortOutOfRangeException constructor.
     */
    public function __construct(mixed $min = 1024, mixed $max = 65535)
    {
        parent::__construct(trans('exceptions.allocations.port_out_of_range', compact('min', 'max')));
    }
}
