<?php

namespace App\Services\Helpers;

use App\Facades\Plugins;
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
        $baseLanguages = collect(File::directories(base_path($path)))->mapWithKeys(function ($path) {
            $code = basename($path);

            return [$code => title_case(Locale::getDisplayName($code, $code))];
        })->toArray();

        $pluginLanguages = collect(Plugins::getPluginLanguages())->mapWithKeys(fn ($code) => [$code => title_case(Locale::getDisplayName($code, $code))])->toArray();

        return array_unique(array_merge($baseLanguages, $pluginLanguages));
    }
}
