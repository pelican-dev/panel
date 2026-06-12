<?php

namespace App\Console\Commands\Egg;

use App\Enums\EggFormat;
use App\Exceptions\Service\InvalidFileUploadException;
use App\Models\Egg;
use App\Services\Eggs\Sharing\EggExporterService;
use App\Services\Eggs\Sharing\EggImporterService;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;

class NormalizeEggCommand extends Command
{
    protected $description = 'Upgrades egg json/yaml to latest format as yaml';

    protected $signature = 'p:egg:normalize {file} {--delete-original}';

    public function handle(EggImporterService $importer, EggExporterService $exporter): int
    {
        $inputFile = $this->argument('file');
        $unparsed = file_get_contents($inputFile);
        if ($unparsed === false) {
            $this->error("Failed to read file: {$inputFile}");

            return Command::FAILURE;
        }
        $extension = strtolower(pathinfo($inputFile, PATHINFO_EXTENSION));

        $format = match ($extension) {
            'yaml', 'yml' => EggFormat::YAML,
            'json' => EggFormat::JSON,
            default => throw new Exception("Unsupported file format while importing {$inputFile}."),
        };

        $this->info("Importing {$inputFile}");

        try {
            $eggArray = $importer->parse($unparsed, $format);
        } catch (InvalidFileUploadException $e) {
            throw new Exception("Unsupported file version while importing {$inputFile}.", previous: $e);
        }

        // We upgraded our in-memory array when we imported, so set to latest version before export
        if (!array_key_exists('meta', $eggArray)) {
            throw new Exception("File does not contain existing meta, is it actually an egg? {$inputFile}");
        }
        $eggArray['meta']['version'] = Egg::EXPORT_VERSION;
        $eggArray['meta']['update_url'] = self::replaceExtension($eggArray['meta']['update_url']);
        $eggArray['exported_at'] = Carbon::now()->toAtomString();

        if (array_key_exists('variables', $eggArray)) {
            foreach ($eggArray['variables'] as &$var) {
                unset($var['field_type']);
                if (!array_key_exists('rules', $var)) {
                    continue;
                }
                $var['rules'] = is_array($var['rules']) ? $var['rules'] : explode('|', $var['rules']);
            }
        }

        $outputFile = self::replaceExtension($inputFile);
        if ($this->option('delete-original') && $outputFile !== $inputFile) {
            $this->info("Deleting {$inputFile} as requested");
            if (!unlink($inputFile)) {
                $this->warn("Failed to delete original file: {$inputFile}");
            }
        }

        $this->info("Exporting to {$outputFile}");
        $yaml = Yaml::dump($exporter->yamlExport($eggArray), 10, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK | Yaml::DUMP_OBJECT_AS_MAP);

        if (file_put_contents($outputFile, $yaml) === false) {
            $this->error("Failed to write output file: {$outputFile}");

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private static function replaceExtension(string $path): string
    {
        return preg_replace('/^(.*\.)(?:yml|json|yaml)$/', '${1}yaml', $path);
    }
}
