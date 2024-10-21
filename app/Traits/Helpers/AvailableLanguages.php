<?php

namespace App\Traits\Helpers;

use Locale;
use Illuminate\Filesystem\Filesystem;

trait AvailableLanguages
{
    private ?Filesystem $filesystem = null;

    public const TRANSLATED = [
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

    /**
     * Return all the available languages on the Panel based on those
     * that are present in the language folder.
     */
    public function getAvailableLanguages(): array
    {
        return collect($this->getFilesystemInstance()->directories(base_path('lang')))->mapWithKeys(function ($path) {
            $code = basename($path);

            $value = Locale::getDisplayName($code, $code);

            return [$code => title_case($value)];
        })->toArray();
    }

    public function isLanguageTranslated(string $countryCode = 'en'): bool
    {
        return in_array($countryCode, self::TRANSLATED, true);
    }

    /**
     * Return an instance of the filesystem for getting a folder listing.
     */
    private function getFilesystemInstance(): Filesystem
    {
        // @phpstan-ignore-next-line
        return $this->filesystem = $this->filesystem ?: app()->make(Filesystem::class);
    }
}
