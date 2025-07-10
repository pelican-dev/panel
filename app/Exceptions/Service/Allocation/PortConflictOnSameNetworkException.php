<?php

namespace App\Exceptions\Service\Allocation;

use App\Exceptions\DisplayException;

class PortConflictOnSameNetworkException extends DisplayException
{
    /**
     * PortConflictOnSameNetworkException constructor.
     */
    public function __construct(int $port, string $ip, int $conflictingNodeId)
    {
        parent::__construct(trans('exceptions.allocations.port_conflict_same_network', [
            'port' => $port,
            'ip' => $ip,
            'node_id' => $conflictingNodeId,
        ]));
    }
} 