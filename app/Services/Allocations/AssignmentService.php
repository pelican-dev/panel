<?php

namespace App\Services\Allocations;

use App\Models\Allocation;
use IPTools\Network;
use App\Models\Node;
use App\Models\Server;
use Illuminate\Database\ConnectionInterface;
use App\Exceptions\DisplayException;
use App\Exceptions\Service\Allocation\CidrOutOfRangeException;
use App\Exceptions\Service\Allocation\PortOutOfRangeException;
use App\Exceptions\Service\Allocation\InvalidPortMappingException;
use App\Exceptions\Service\Allocation\TooManyPortsInRangeException;

class AssignmentService
{
    public const CIDR_MAX_BITS = 25;

    public const CIDR_MIN_BITS = 32;

    public const PORT_FLOOR = 1024;

    public const PORT_CEIL = 65535;

    public const PORT_RANGE_LIMIT = 1000;

    public const PORT_RANGE_REGEX = '/^(\d{4,5})-(\d{4,5})$/';

    public function __construct(protected ConnectionInterface $connection) {}

    /**
     * Insert allocations into the database and link them to a specific node.
     *
     * @param  array{allocation_ip: string, allocation_ports: array<int|string>}  $data
     * @return array<int>
     *
     * @throws \App\Exceptions\DisplayException
     * @throws \App\Exceptions\Service\Allocation\CidrOutOfRangeException
     * @throws \App\Exceptions\Service\Allocation\InvalidPortMappingException
     * @throws \App\Exceptions\Service\Allocation\PortOutOfRangeException
     * @throws \App\Exceptions\Service\Allocation\TooManyPortsInRangeException
     */
    public function handle(Node $node, array $data, ?Server $server = null): array
    {
        $explode = explode('/', $data['allocation_ip']);
        if (count($explode) !== 1) {
            if (!ctype_digit($explode[1]) || ($explode[1] > self::CIDR_MIN_BITS || $explode[1] < self::CIDR_MAX_BITS)) {
                throw new CidrOutOfRangeException();
            }
        }

        try {
            $parsed = Network::parse($data['allocation_ip']);
        } catch (\Exception $exception) {
            throw new DisplayException("Could not parse provided allocation IP address ({$data['allocation_ip']}): {$exception->getMessage()}", $exception);
        }

        $this->connection->beginTransaction();

        $ids = [];
        foreach ($parsed as $ip) {
            foreach ($data['allocation_ports'] as $port) {
                if (!is_digit($port) && !preg_match(self::PORT_RANGE_REGEX, $port)) {
                    throw new InvalidPortMappingException($port);
                }

                $newAllocations = [];
                if (preg_match(self::PORT_RANGE_REGEX, $port, $matches)) {
                    $block = range($matches[1], $matches[2]);

                    if (count($block) > self::PORT_RANGE_LIMIT) {
                        throw new TooManyPortsInRangeException();
                    }

                    if ((int) $matches[1] < self::PORT_FLOOR || (int) $matches[2] > self::PORT_CEIL) {
                        throw new PortOutOfRangeException();
                    }

                    foreach ($block as $unit) {
                        $newAllocations[] = [
                            'node_id' => $node->id,
                            'ip' => $ip->__toString(),
                            'port' => (int) $unit,
                            'ip_alias' => array_get($data, 'allocation_alias'),
                            'server_id' => $server->id ?? null,
                        ];
                    }
                } else {
                    if ((int) $port < self::PORT_FLOOR || (int) $port > self::PORT_CEIL) {
                        throw new PortOutOfRangeException();
                    }

                    $newAllocations[] = [
                        'node_id' => $node->id,
                        'ip' => $ip->__toString(),
                        'port' => (int) $port,
                        'ip_alias' => array_get($data, 'allocation_alias'),
                        'server_id' => $server->id ?? null,
                    ];
                }

                foreach ($newAllocations as $newAllocation) {
                    $allocation = Allocation::query()->create($newAllocation);
                    $ids[] = $allocation->id;
                }
            }
        }

        $this->connection->commit();

        return $ids;
    }
}
