<?php

namespace App\Traits\Helpers;

use Locale;
use Illuminate\Filesystem\Filesystem;

trait AvailableLanguages
{
    private ?Filesystem $filesystem = null;

    /**
     * Return all the available languages on the Panel based on those
     * that are present in the language folder.
     */
    public function getAvailableLanguages(): array
    {
        return collect($this->getFilesystemInstance()->directories(base_path('lang')))->mapWithKeys(function ($path) {
            $code = basename($path);

            $value = Locale::getDisplayName($code, app()->currentLocale());

            return [$code => title_case($value)];
        })->toArray();
    }

    /**
     * Return an instance of the filesystem for getting a folder listing.
     */
    private function getFilesystemInstance(): Filesystem
    {
        return $this->filesystem = $this->filesystem ?: app()->make(Filesystem::class);
    }
}
