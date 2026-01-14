<?php

namespace App\Services\Helpers;

use Illuminate\Support\Facades\File;
use Locale;

class LanguageService
{
    public const TRANSLATED_COMPLETELY = [
        'en',
    ];

    public function __construct(protected PluginService $pluginService) {}

    public function isLanguageTranslated(string $countryCode = 'en'): bool
    {
        return in_array($countryCode, self::TRANSLATED_COMPLETELY, true);
    }

    public function getLanguageDisplayName(string $code): string
    {
        $key = 'profile.current_language_name';
        $trans = trans($key, locale: $code);

        return $trans !== $key ? $trans : title_case(Locale::getDisplayName($code, $code));
    }

    /**
     * @return array<array-key, string>
     */
    public function getAvailableLanguages(string $path = 'lang'): array
    {
        $baseLanguages = collect(File::directories(base_path($path)))->mapWithKeys(function ($path) {
            $code = basename($path);

            return [$code => $this->getLanguageDisplayName($code)];
        })->toArray();

        $pluginLanguages = collect($this->pluginService->getPluginLanguages())->mapWithKeys(fn ($code) => [$code => $this->getLanguageDisplayName($code)])->toArray();

        return array_sort(array_unique(array_merge($baseLanguages, $pluginLanguages)));
    }
}
