<?php

namespace App\Tests\Seeders;

use App\Exceptions\Service\InvalidFileUploadException;
use App\Services\Eggs\Sharing\EggImporterService;
use DirectoryIterator;
use Illuminate\Http\UploadedFile;
use Throwable;

class EggSeeder
{
    /**
     * @throws InvalidFileUploadException|Throwable
     */
    public function run(): void
    {
        // @phpstan-ignore myCustomRules.forbiddenGlobalFunctions
        $importer = app(EggImporterService::class);

        $path = base_path('tests/_fixtures');
        $files = new DirectoryIterator($path);

        /** @var DirectoryIterator $file */
        foreach ($files as $file) {
            if (!$file->isFile() || !$file->isReadable()) {
                continue;
            }

            $filePath = $file->getRealPath();
            $uploaded = new UploadedFile($filePath, basename($filePath));

            $importer->fromFile($uploaded);
        }
    }
}
