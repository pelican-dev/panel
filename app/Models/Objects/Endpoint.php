<?php

namespace App\Models\Objects;

use InvalidArgumentException;

class Endpoint
{
    public const CIDR_MAX_BITS = 27;
    public const CIDR_MIN_BITS = 32;
    public const PORT_FLOOR = 1024;
    public const PORT_CEIL = 65535;
    public const PORT_RANGE_LIMIT = 1000;
    public const PORT_RANGE_REGEX = '/^(\d{4,5})-(\d{4,5})$/';

    public int $port;
    public string $ip;

    public function __construct(string $address = null, int $port = null) {
        if ($address === null) {
            $address = '0.0.0.0';
        }

        $ip = $address;
        if (str_contains($address, ':')) {
            [$ip, $port] = explode(':', $address);

            throw_unless(is_numeric($port), new InvalidArgumentException("Port ($port) must be a number"));

            $port = (int) $port;
        }

        throw_unless(is_int($port), new InvalidArgumentException("Port ($port) must be an integer"));
        throw_unless(filter_var($ip, FILTER_VALIDATE_IP) !== false, new InvalidArgumentException("$ip is an invalid IP address"));

        $this->ip = $ip;
        $this->port = $port;
    }
}
