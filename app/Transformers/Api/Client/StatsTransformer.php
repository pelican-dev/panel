<?php

namespace App\Transformers\Api\Client;

use Illuminate\Support\Arr;

class StatsTransformer extends BaseClientTransformer
{
    public function getResourceName(): string
    {
        return 'stats';
    }

    /**
     * @param array{
     *     state: string,
     *     is_suspended: bool,
     *     utilization: array{
     *         memory_bytes: int,
     *         cpu_absolute: int,
     *         disk_bytes: int,
     *         rx_bytes: int,
     *         tx_bytes: int,
     *         uptime: int,
     *     },
     * } $data
     */
    public function transform($data): array
    {
        return [
            'current_state' => Arr::get($data, 'state', 'stopped'),
            'is_suspended' => Arr::get($data, 'is_suspended', false),
            'resources' => [
                'memory_bytes' => Arr::get($data, 'utilization.memory_bytes', 0),
                'cpu_absolute' => Arr::get($data, 'utilization.cpu_absolute', 0),
                'disk_bytes' => Arr::get($data, 'utilization.disk_bytes', 0),
                'network_rx_bytes' => Arr::get($data, 'utilization.network.rx_bytes', 0),
                'network_tx_bytes' => Arr::get($data, 'utilization.network.tx_bytes', 0),
                'uptime' => Arr::get($data, 'utilization.uptime', 0),
            ],
        ];
    }
}
