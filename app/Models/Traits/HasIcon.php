<?php

namespace App\Models\Traits;

use App\Models\Model;
use Exception;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin Model
 */
trait HasIcon
{
    /**
     * Supported icon formats: file extension => MIME type
     *
     * @var array<string, string>
     */
    public static array $iconFormats = [
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'webp' => 'image/webp',
    ];

    public static function getIconStoragePath(): string
    {
        return 'icons/' . static::RESOURCE_NAME;
    }

    public function getIconAttribute(): ?string
    {
        foreach (array_keys(static::$iconFormats) as $ext) {
            $path = $this->getIconStoragePath() . "/$this->uuid.$ext";
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->url($path);
            }
        }

        return null;
    }

    public function writeIcon(string $extension, string $data): string
    {
        $normalizedExtension = match (strtolower($extension)) {
            'jpeg', 'jpg' => 'jpg',
            'png' => 'png',
            'webp' => 'webp',
            default => null,
        };

        if (is_null($normalizedExtension)) {
            throw new Exception(trans('admin/egg.import.unknown_extension', ['extension' => $extension]));
        }

        $fileName = static::getIconStoragePath() . "/$this->uuid.$normalizedExtension";

        if (!Storage::disk('public')->put($fileName, $data)) {
            throw new Exception(trans('admin/egg.import.could_not_write'));
        }

        foreach (['png', 'jpg', 'jpeg', 'webp', 'svg'] as $ext) {
            if ($ext === $normalizedExtension) {
                continue;
            }

            $path = static::getIconStoragePath() . "/$this->uuid.$ext";
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        return $fileName;
    }
}
