<?php

namespace App\Tests\Seeders;

use App\Services\Eggs\Sharing\EggImporterService;
use Illuminate\Http\UploadedFile;

class EggSeeder
{
    public function run(): void
    {
        $dir = base_path('tests/_fixtures');

        if (!is_dir($dir)) {
            throw new \RuntimeException('Egg fixtures directory not found at: ' . $dir);
        }

        $files = glob($dir . DIRECTORY_SEPARATOR . '*.yaml') ?: [];

        if (empty($files)) {
            throw new \RuntimeException('No egg fixtures found in: ' . $dir);
        }

        /** @var EggImporterService $importer */
        $importer = app(EggImporterService::class);

        foreach ($files as $filePath) {
            if (!is_file($filePath)) {
                continue;
            }

            $uploaded = new UploadedFile($filePath, basename($filePath), 'application/yaml', UPLOAD_ERR_OK, true);
            $importer->fromFile($uploaded);
        }
    }
}
