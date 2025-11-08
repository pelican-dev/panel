<?php

namespace App\Services\Avatars;

use Illuminate\Support\Facades\Cache;

class LocalAvatarService
{
    public function generateSvgAvatar(
        string $name,
        string $backgroundColor = '111827',
        string $textColor = 'FFFFFF',
        int $size = 128
    ): string {
        $initials = $this->getInitials($name);
        $fontSize = $size * 0.4;

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$size}" height="{$size}" viewBox="0 0 {$size} {$size}">
    <rect width="{$size}" height="{$size}" fill="#{$backgroundColor}"/>
    <text x="50%" y="50%" dominant-baseline="central" text-anchor="middle" font-family="Arial, sans-serif" font-size="{$fontSize}" fill="#{$textColor}" font-weight="500">{$initials}</text>
</svg>
SVG;

        return $svg;
    }

    protected function getInitials(string $name): string
    {
        $initials = str($name)
            ->trim()
            ->explode(' ')
            ->map(fn (string $segment): string => filled($segment) ? mb_substr($segment, 0, 1) : '')
            ->join('');

        return strtoupper((string) $initials);
    }

    public function generateDataUri(
        string $name,
        string $backgroundColor = '111827',
        string $textColor = 'FFFFFF',
        int $size = 128
    ): string {
        $cacheKey = "avatar:{$name}:{$backgroundColor}:{$textColor}:{$size}";

        return Cache::remember($cacheKey, now()->addDay(), function () use ($name, $backgroundColor, $textColor, $size) {
            $svg = $this->generateSvgAvatar($name, $backgroundColor, $textColor, $size);

            return 'data:image/svg+xml;base64,' . base64_encode($svg);
        });
    }

    /**
     * @return string Hex color without #
     */
    public function generateColorFromName(string $name): string
    {
        $hash = md5($name);
        $hue = hexdec(substr($hash, 0, 2)) / 255 * 360;
        $saturation = 60;
        $lightness = 45;

        return $this->hslToHex($hue, $saturation, $lightness);
    }

    /**
     * Convert HSL to Hex color.
     *
     * @param  float  $h  Hue (0-360)
     * @param  float  $s  Saturation (0-100)
     * @param  float  $l  Lightness (0-100)
     * @return string Hex color without #
     */
    protected function hslToHex(float $h, float $s, float $l): string
    {
        $h /= 360;
        $s /= 100;
        $l /= 100;

        if ($s == 0) {
            $r = $g = $b = $l;
        } else {
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;

            $r = $this->hueToRgb($p, $q, $h + 1 / 3);
            $g = $this->hueToRgb($p, $q, $h);
            $b = $this->hueToRgb($p, $q, $h - 1 / 3);
        }

        return sprintf('%02x%02x%02x', round($r * 255), round($g * 255), round($b * 255));
    }

    /**
     * Helper function to convert hue to RGB.
     */
    protected function hueToRgb(float $p, float $q, float $t): float
    {
        if ($t < 0) {
            $t += 1;
        }
        if ($t > 1) {
            $t -= 1;
        }
        if ($t < 1 / 6) {
            return $p + ($q - $p) * 6 * $t;
        }
        if ($t < 1 / 2) {
            return $q;
        }
        if ($t < 2 / 3) {
            return $p + ($q - $p) * (2 / 3 - $t) * 6;
        }

        return $p;
    }
}
