<?php

if (!function_exists('is_digit')) {
    /**
     * Deal with normal (and irritating) PHP behavior to determine if
     * a value is a non-float positive integer.
     */
    function is_digit(mixed $value): bool
    {
        return !is_bool($value) && ctype_digit(strval($value));
    }
}

if (!function_exists('is_ip')) {
    function is_ip(?string $address): bool
    {
        return $address !== null && filter_var($address, FILTER_VALIDATE_IP) !== false;
    }
}

if (!function_exists('object_get_strict')) {
    /**
     * Get an object using dot notation. An object key with a value of null is still considered valid
     * and will not trigger the response of a default value (unlike object_get).
     */
    function object_get_strict(object $object, ?string $key, mixed $default = null): mixed
    {
        if (is_null($key) || trim($key) == '') {
            return $object;
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_object($object) || !property_exists($object, $segment)) {
                return value($default);
            }

            $object = $object->{$segment};
        }

        return $object;
    }
}

if (!function_exists('is_installed')) {
    function is_installed(): bool
    {
        // This defaults to true so existing panels count as "installed"
        return env('APP_INSTALLED', true);
    }
}

if (!function_exists('convert_bytes_to_readable')) {
    function convert_bytes_to_readable($bytes, int $decimals = 2): string
    {
        $conversionUnit = config('panel.use_binary_prefix') ? 1024 : 1000;
        $suffix = config('panel.use_binary_prefix') ? ['B', 'KiB', 'MiB', 'GiB', 'TiB'] : ['B', 'KB', 'MB', 'GB', 'TB'];

        $base = log($bytes) / log($conversionUnit);
        $f_base = floor($base);

        return round(pow($conversionUnit, $base - $f_base), $decimals) . ' ' . $suffix[$f_base];
    }
}
