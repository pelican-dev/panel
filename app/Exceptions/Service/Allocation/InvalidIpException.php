<?php

namespace App\Exceptions\Service\Allocation;

use App\Exceptions\DisplayException;

class InvalidIpException extends DisplayException
{
    /**
     * InvalidIpException constructor.
     */
    public function __construct(mixed $ip)
    {
        parent::__construct(trans('exceptions.allocations.invalid_ip', compact('ip')));
    }
}
