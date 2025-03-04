<?php

namespace App\Services\Helpers;

use Illuminate\Support\Facades\File;
use Locale;

class LanguageService
{
    public const TRANSLATED_COMPLETELY = [
        'en',
    ];

    public function isLanguageTranslated(string $countryCode = 'en'): bool
    {
        return in_array($countryCode, self::TRANSLATED_COMPLETELY, true);
    }

    /**
     * @return array<array-key, string>
     */
    public function getAvailableLanguages(string $path = 'lang'): array
    {
        return collect(File::directories(base_path($path)))->mapWithKeys(function ($path) {
            $code = basename($path);

            return [$code => title_case(Locale::getDisplayName($code, $code))];
        })->toArray();
    }
}
