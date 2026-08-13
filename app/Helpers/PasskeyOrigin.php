<?php

namespace App\Helpers;

class PasskeyOrigin
{
    /**
     * Ports that are implicit for a scheme, and therefore never part of an origin.
     */
    private const DefaultPorts = [
        'http' => 80,
        'https' => 443,
    ];

    /**
     * Reduce a URL to the origin browsers report during a WebAuthn ceremony:
     * "scheme://host[:port]", without any path, query or default port.
     *
     * Returns null when the value has no usable host.
     */
    public static function normalize(?string $url): ?string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return null;
        }

        // Bare hosts are assumed to be https, since WebAuthn only runs in secure contexts.
        if (!str_contains($url, '://')) {
            $url = 'https://' . $url;
        }

        $parts = parse_url($url);

        if (!is_array($parts) || !isset($parts['host'])) {
            return null;
        }

        $scheme = $parts['scheme'] ?? 'https';
        $port = $parts['port'] ?? null;

        $origin = $scheme . '://' . $parts['host'];

        if ($port !== null && $port !== (self::DefaultPorts[$scheme] ?? null)) {
            $origin .= ':' . $port;
        }

        return $origin;
    }

    /**
     * Normalize a list of URLs into a unique list of origins, dropping empty entries.
     *
     * @param  array<array-key, string|null>  $urls
     * @return list<string>
     */
    public static function all(array $urls): array
    {
        $origins = array_filter(array_map(self::normalize(...), $urls));

        return array_values(array_unique($origins));
    }
}
