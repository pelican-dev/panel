<?php

namespace App\Extensions\Avatar\Schemas\Local;

use Filament\AvatarProviders\Contracts\AvatarProvider;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class LocalAvatarProvider implements AvatarProvider
{
    private string $name;

    private string $initials;

    private string $backgroundColor = '111827';

    private string $textColor = 'FFFFFF';

    private float $size = 128;

    private float $fontSizeMultiplier = 0.4;

    public function get(Model $record): string
    {
        $name = Filament::getNameForDefaultAvatar($record);

        return $this->generateDataUri($name);

    }

    private function generateDataUri(string $name): string
    {
        $this->name = $name;
        $this->initials = $this->getInitials($this->name);

        $this->backgroundColor = $this->generateColorFromInitials();
        $this->backgroundColor = ltrim($this->backgroundColor, '#');
        $this->textColor = ltrim($this->textColor, '#');
        $cacheKey = "avatar:{$this->initials}:{$this->backgroundColor}:{$this->textColor}:{$this->size}";

        return cache()->remember($cacheKey, now()->addDay(), function () {
            $svg = $this->generateSvgAvatar();

            return 'data:image/svg+xml;base64,' . base64_encode($svg);
        });
    }

    public function generateSvgAvatar(): string
    {
        $fontSize = $this->size * $this->fontSizeMultiplier;

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$this->size}" height="{$this->size}" viewBox="0 0 {$this->size} {$this->size}">
    <rect width="{$this->size}" height="{$this->size}" fill="#{$this->backgroundColor}"/>
    <text x="50%" y="50%" dominant-baseline="central" text-anchor="middle" font-family="Arial, sans-serif" font-size="{$fontSize}" fill="#{$this->textColor}" font-weight="500">{$this->initials}</text>
</svg>
SVG;

        return $svg;
    }

    private function getInitials(string $name): string
    {
        $initials = str($name)
            ->trim()
            ->explode(' ')
            ->map(fn (string $segment): string => filled($segment) ? mb_substr($segment, 0, 1) : '')
            ->join('');

        return strtoupper((string) $initials);
    }

    private function generateColorFromInitials(): string
    {
        $hash = md5($this->initials);
        $hue = hexdec(substr($hash, 0, 2)) / 255 * 360;

        return $this->hslToHex($hue);
    }

    private function hslToHex(float $hue, ?float $saturation = 60, float $lightness = 45): string
    {
        $hue /= 360;
        $saturation /= 100;
        $lightness /= 100;
        $red = $green = $blue = $lightness;

        if ((int) $saturation !== 0) {
            $max = $lightness < 0.5 ? $lightness * (1 + $saturation) : $lightness + $saturation - $lightness * $saturation;
            $min = 2 * $lightness - $max;

            $red = round($this->hueToRgb($min, $max, $hue + 1 / 3) * 255);
            $green = round($this->hueToRgb($min, $max, $hue) * 255);
            $blue = round($this->hueToRgb($min, $max, $hue - 1 / 3) * 255);
        }

        return sprintf('%02x%02x%02x', $red, $green, $blue);
    }

    private function hueToRgb(float $min, float $max, float $hue): float
    {
        if ($hue < 0) {
            $hue += 1;
        }
        if ($hue > 1) {
            $hue -= 1;
        }
        if ($hue < 1 / 6) {
            return $min + ($max - $min) * 6 * $hue;
        }
        if ($hue < 1 / 2) {
            return $max;
        }
        if ($hue < 2 / 3) {
            return $min + ($max - $min) * (2 / 3 - $hue) * 6;
        }

        return $min;
    }
}
