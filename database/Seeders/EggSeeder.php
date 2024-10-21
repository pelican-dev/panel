<?php

namespace Database\Seeders;

use App\Models\Egg;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use App\Services\Eggs\Sharing\EggImporterService;

class EggSeeder extends Seeder
{
    protected EggImporterService $importerService;

    /**
     * @var string[]
     */
    public static array $imports = [
        'Minecraft',
        'Source Engine',
        'Voice Servers',
        'Rust',
    ];

    /**
     * EggSeeder constructor.
     */
    public function __construct(
        EggImporterService $importerService
    ) {
        $this->importerService = $importerService;
    }

    /**
     * Run the egg seeder.
     */
    public function run(): void
    {
        foreach (static::$imports as $import) {
            $this->parseEggFiles($import);
        }
    }

    /**
     * Loop through the list of egg files and import them.
     */
    protected function parseEggFiles($name): void
    {
        $files = new \DirectoryIterator(database_path('Seeders/eggs/' . kebab_case($name)));

        $this->command->alert('Updating Eggs for: ' . $name);
        /** @var \DirectoryIterator $file */
        foreach ($files as $file) {
            if (!$file->isFile() || !$file->isReadable()) {
                continue;
            }

            try {
                $decoded = json_decode(file_get_contents($file->getRealPath()), true, 512, JSON_THROW_ON_ERROR);
            } catch (Exception) {
                continue;
            }

            $file = new UploadedFile($file->getPathname(), $file->getFilename(), 'application/json');

            $egg = Egg::query()
                ->where('author', $decoded['author'])
                ->where('name', $decoded['name'])
                ->first();

            if ($egg instanceof Egg) {
                $this->importerService->fromFile($file, $egg);
                $this->command->info('Updated ' . $decoded['name']);
            } else {
                $this->importerService->fromFile($file);
                $this->command->comment('Created ' . $decoded['name']);
            }
        }

        $this->command->line('');
    }
}
