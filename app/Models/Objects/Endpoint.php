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
    public const INADDR_ANY = '0.0.0.0';

    public int $port;
    public string $ip;

    public function __construct(string|int $port, string $ip = null)
    {
        $this->ip = $ip ?? self::INADDR_ANY;
        $this->port = (int) $port;

        if (str_contains($port, ':')) {
            [$this->ip, $this->port] = explode(':', $port);
        }

        throw_unless(filter_var($this->ip, FILTER_VALIDATE_IP) !== false, new InvalidArgumentException("$this->ip is an invalid IP address"));
        throw_unless($this->port > self::PORT_FLOOR, "Port $this->port must be greater than " . self::PORT_FLOOR);
        throw_unless($this->port < self::PORT_CEIL, "Port $this->port must be less than " . self::PORT_CEIL);
    }

    public function __toString()
    {
        if ($this->ip === self::INADDR_ANY) {
            return (string) $this->port;
        }

        return "$this->ip:$this->port";
    }
}
