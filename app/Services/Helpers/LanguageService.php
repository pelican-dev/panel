<?php

namespace App\Services\Helpers;

use Illuminate\Support\Facades\File;
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

    public function isLanguageTranslated(string $countryCode = 'en'): bool
    {
        return in_array($countryCode, self::TRANSLATED_COMPLETELY, true);
    }

    public function getAvailableLanguages(string $path = 'lang'): array
    {
        return collect(File::directories(base_path($path)))->mapWithKeys(function ($path) {
            $code = basename($path);

            return [$code => title_case(Locale::getDisplayName($code, $code))];
        })->toArray();
    }
}
