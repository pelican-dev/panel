<?php

namespace App\Services\Allocations;

use App\Models\Allocation;
use IPTools\Network;
use App\Models\Node;
use App\Models\Server;
use App\Exceptions\Service\Allocation\CidrOutOfRangeException;
use App\Exceptions\Service\Allocation\InvalidIpException;
use App\Exceptions\Service\Allocation\PortOutOfRangeException;
use App\Exceptions\Service\Allocation\InvalidPortMappingException;
use App\Exceptions\Service\Allocation\PortsAlreadyExistsException;
use App\Exceptions\Service\Allocation\TooManyPortsInRangeException;
use Exception;
use Filament\Notifications\Notification;
use IPTools\IP;

class AssignmentService
{
    public const IPV4_CIDR_MAX_BITS = 25;

    public const IPV4_CIDR_MIN_BITS = 32;

    public const IPV4_REGEX = '((25[0-5]|2[0-4]\d|1\d{2}|[1-9]?\d)\.){3}(25[0-5]|2[0-4]\d|1\d{2}|[1-9]?\d)(\/\d{2})?';

    public const IPV6_CIDR_MAX_BITS = 121;

    public const IPV6_CIDR_MIN_BITS = 128;

    public const IPV6_REGEX = '(?<s>[0-9a-fA-F]{1,4})(:(?&s)){7}|((?&s):){0,7}(?&s)?|::((?&s):){0,6}(?&s)?(\/\d{3})?';

    // public const IP_REGEX = '/^\b(' . self::IPV4_REGEX . ')\b$/';
    public const IP_REGEX = '/^\b(' . self::IPV4_REGEX . ')|(' . self::IPV6_REGEX . ')\b$/';

    public const PORT_FLOOR = 1024;

    public const PORT_CEIL = 65535;

    public const PORT_RANGE_LIMIT = 1000;

    public const PORT_RANGE_REGEX = '/^(\d{4,5})-(\d{4,5})$/';

    /**
     * AssignmentService constructor.
     */
    public function __construct()
    {
    }

    /**
     * Insert allocations into the database and link them to a specific node.
     *
     * @throws \App\Exceptions\Service\Allocation\CidrOutOfRangeException
     * @throws \App\Exceptions\Service\Allocation\InvalidIpException
     * @throws \App\Exceptions\Service\Allocation\InvalidPortMappingException
     * @throws \App\Exceptions\Service\Allocation\PortOutOfRangeException
     * @throws \App\Exceptions\Service\Allocation\PortsAlreadyExistsException
     * @throws \App\Exceptions\Service\Allocation\TooManyPortsInRangeException
     */
    public function handle(Node $node, array $data, ?Server $server = null): array
    {
        $underlying = gethostbyname($data['allocation_ip']);
        $version = str($underlying)->contains(':') ? 6 : 4;
        $explode = explode('/', $data['allocation_ip']);
        $cidr = $explode[1] ?? null;

        if ($version === 6) {
            // TODO: validate ipv6 support
            Notification::make()
                ->title('Unsupported')
                ->body('IPv6 support is not fully validated; use at your own risks.')
                ->warning()
                ->send();
        }

        if ($cidr && (!ctype_digit($cidr) || ($cidr > constant('self::IPV'.$version.'_CIDR_MIN_BITS') || $cidr < constant('self::IPV'.$version.'_CIDR_MAX_BITS')))) {
            throw new CidrOutOfRangeException(version: $version);
        }

        try {
            $ips = Network::parse($underlying)->getHosts();
        } catch (Exception) {
            throw new InvalidIpException($data['allocation_ip']);
        }

        $ids = collect();
        $failed = collect();

        $alias = array_get($data, 'allocation_alias');
        $allocation_ports = $data['allocation_ports'];

        $ports = collect($allocation_ports)
            ->flatMap(function ($port) {
                if (!is_digit($port)) {
                    if (preg_match(self::PORT_RANGE_REGEX, $port, $matches)) {
                        [$start, $end] = $matches;

                        if ($start > $end) {
                            [$start, $end] = [$end, $start];
                        }

                        return range((int) $start, (int) $end);
                    }

                    throw new InvalidPortMappingException($port);
                }

                if ((int) $port < self::PORT_FLOOR || (int) $port > self::PORT_CEIL) {
                    throw new PortOutOfRangeException();
                }

                if (is_numeric($port)) {
                    return [(int) $port];
                }
            })
            ->unique();

        if ($ports->count() > self::PORT_RANGE_LIMIT) {
            throw new TooManyPortsInRangeException();
        }

        collect($ips)
            ->each(function (IP $ip) use ($ports, $ids, $failed, $node, $alias, $server) {
                $ip = $ip->__toString();
                $ports->each(function (int $port) use ($ids, $failed, $node, $ip, $alias, $server) {

                    $insert = [
                        'node_id' => $node->id,
                        'ip' => $ip,
                        'port' => $port,
                        'ip_alias' => $alias,
                        'server_id' => $server->id ?? null,
                    ];

                    try {
                        $allocation = Allocation::query()->create($insert);
                        $ids->push($allocation->id);
                    } catch (Exception) {
                        $failed->put($ip, $insert['port']);
                    }
                });
            });

        if ($failed->isNotEmpty()) {
            // TODO: proper notification
            $exception = new PortsAlreadyExistsException($failed->keys()->join(', '), $failed->values()->join(', '));
            Notification::make()
                ->title(str($exception::class)->afterLast('\\'))
                ->body($exception->getMessage())
                ->danger()
                ->send();
        }

        return $ids->values()->all();
    }
}
