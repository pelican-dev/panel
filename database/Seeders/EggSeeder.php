<?php

namespace Database\Seeders;

use App\Models\Egg;
use DirectoryIterator;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use App\Services\Eggs\Sharing\EggImporterService;
use Symfony\Component\Yaml\Yaml;
use Throwable;

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
        $path = database_path('Seeders/eggs/' . kebab_case($name));
        $files = new DirectoryIterator($path);

        $this->command->alert('Updating Eggs for: ' . $name);

        /** @var DirectoryIterator $file */
        foreach ($files as $file) {
            if (!$file->isFile() || !$file->isReadable()) {
                continue;
            }

            $extension = strtolower($file->getExtension());
            $filePath = $file->getRealPath();

            try {
                $decoded = match ($extension) {
                    'json' => json_decode(file_get_contents($filePath), true, 512, JSON_THROW_ON_ERROR),
                    'yaml', 'yml' => Yaml::parseFile($filePath),
                    default => null,
                };
            } catch (Throwable) {
                $this->command->warn("Failed to parse {$file->getFilename()}, skipping.");

                continue;
            }

            if (!is_array($decoded) || !isset($decoded['name'], $decoded['author'])) {
                $this->command->warn("Invalid structure in {$file->getFilename()}, skipping.");

                continue;
            }

            $uploaded = new UploadedFile($filePath, $file->getFilename());

            $egg = Egg::query()
                ->where('author', $decoded['author'])
                ->where('name', $decoded['name'])
                ->first();

            if ($egg instanceof Egg) {
                $this->importerService->fromFile($uploaded, $egg);
                $this->command->info('Updated ' . $decoded['name']);
            } else {
                $this->importerService->fromFile($uploaded);
                $this->command->comment('Created ' . $decoded['name']);
            }
        }

        $this->command->line('');
    }
}
