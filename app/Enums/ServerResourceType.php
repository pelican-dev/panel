<?php

namespace App\Enums;

use App\Models\Server;

enum ServerResourceType: string
{
    case Uptime = 'uptime';
    case CPU = 'cpu_absolute';
    case Memory = 'memory_bytes';
    case Disk = 'disk_bytes';

    case CPULimit = 'cpu';
    case MemoryLimit = 'memory';
    case DiskLimit = 'disk';

    /**
     * @return int resource amount in bytes
     */
    public function getResourceAmount(Server $server): int
    {
        if ($this->isLimit()) {
            $resourceAmount = $server->{$this->value} ?? 0;

            if (!$this->isPercentage()) {
                // Our limits are entered as MiB/ MB so we need to convert them to bytes
                $resourceAmount *= config('panel.use_binary_prefix') ? 1024 * 1024 : 1000 * 1000;
            }

            return $resourceAmount;
        }

        return $server->retrieveResources()[$this->value] ?? 0;
    }

    public function isLimit(): bool
    {
        return $this === ServerResourceType::CPULimit || $this === ServerResourceType::MemoryLimit || $this === ServerResourceType::DiskLimit;
    }

    public function isTime(): bool
    {
        return $this === ServerResourceType::Uptime;
    }

    public function isPercentage(): bool
    {
        return $this === ServerResourceType::CPU || $this === ServerResourceType::CPULimit;
    }
}
