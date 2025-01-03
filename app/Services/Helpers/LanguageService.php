<?php

namespace App\Services\Helpers;

use Illuminate\Container\Attributes\Storage;
use Illuminate\Filesystem\Filesystem;
use Locale;

class LanguageService
{
    public const TRANSLATED_COMPLETELY = [
        'ar',
        'cz',
        'da',
        'de',
        'dk',
        'en',
        'es',
        'fi',
        'ja',
        'nl',
        'pl',
        'sk',
        'ru',
        'tr',
    ];

    public function __construct(#[Storage('local')] protected Filesystem $filesystem) {}

    public function isLanguageTranslated(string $countryCode = 'en'): bool
    {
        return in_array($countryCode, self::TRANSLATED_COMPLETELY, true);
    }

    public function getAvailableLanguages(string $path = 'lang'): array
    {
        return collect($this->filesystem->directories(base_path($path)))->mapWithKeys(function ($path) {
            $code = basename($path);

            return [$code => title_case(Locale::getDisplayName($code, $code))];
        })->toArray();
    }
}
