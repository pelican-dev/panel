<?php

namespace App\Exceptions\Service\Allocation;

use App\Exceptions\DisplayException;

class PortsAlreadyExistsException extends DisplayException
{
    /**
     * InvalidIpException constructor.
     */
    public function __construct(mixed $ips, mixed $ports)
    {
        parent::__construct(trans('exceptions.allocations.exists', compact('ips', 'ports')));
    }
}
