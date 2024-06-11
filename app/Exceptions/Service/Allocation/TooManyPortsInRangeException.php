<?php

namespace App\Exceptions\Service\Allocation;

use App\Exceptions\DisplayException;

class TooManyPortsInRangeException extends DisplayException
{
    /**
     * TooManyPortsInRangeException constructor.
     */
    public function __construct()
    {
        parent::__construct(trans('exceptions.ports.too_many_ports'));
    }
}
