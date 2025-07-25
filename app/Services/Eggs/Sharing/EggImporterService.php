<?php

namespace App\Services\Eggs\Sharing;

use App\Exceptions\Service\InvalidFileUploadException;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Arr;
use App\Models\Egg;
use Illuminate\Http\UploadedFile;
use App\Models\EggVariable;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Throwable;

class EggImporterService
{
    public const UPGRADE_VARIABLES = [
        'server.build.env.SERVER_IP' => 'server.allocations.default.ip',
        'server.build.default.ip' => 'server.allocations.default.ip',
        'server.build.env.SERVER_PORT' => 'server.allocations.default.port',
        'server.build.default.port' => 'server.allocations.default.port',
        'server.build.env.SERVER_MEMORY' => 'server.build.memory_limit',
        'server.build.memory' => 'server.build.memory_limit',
        'server.build.env.' => 'server.environment.',
        'server.build.environment.' => 'server.environment.',
    ];

    public function __construct(protected ConnectionInterface $connection) {}

    /**
     * Take an uploaded JSON or YAML file and parse it into a new egg.
     *
     * @throws InvalidFileUploadException|Throwable
     */
    public function fromFile(UploadedFile $file, ?Egg $egg = null): Egg
    {
        $parsed = $this->parseFile($file);

        return $this->connection->transaction(function () use ($egg, $parsed) {
            $uuid = $parsed['uuid'] ?? Uuid::uuid4()->toString();
            $egg = $egg ?? Egg::where('uuid', $uuid)->first() ?? new Egg();

            $egg = $egg->forceFill([
                'uuid' => $uuid,
                'author' => Arr::get($parsed, 'author'),
                'copy_script_from' => null,
            ]);

            for ($i = 0; $i < count($parsed['variables']); $i++) {
                unset($parsed['variables'][$i]['field_type']);
            }

            $egg = $this->fillFromParsed($egg, $parsed);
            $egg->save();

            foreach ($parsed['variables'] ?? [] as $variable) {
                EggVariable::unguarded(function () use ($egg, $variable) {
                    $variable['rules'] = is_array($variable['rules']) ? $variable['rules'] : explode('|', $variable['rules']);

                    $egg->variables()->updateOrCreate([
                        'env_variable' => $variable['env_variable'],
                    ], Collection::make($variable)->except(['egg_id', 'env_variable'])->toArray());
                });
            }

            $imported = array_map(fn ($value) => $value['env_variable'], $parsed['variables'] ?? []);
            $egg->variables()->whereNotIn('env_variable', $imported)->delete();

            return $egg->refresh();
        });
    }

    /**
     * Take a URL (YAML or JSON) and parse it into a new egg or update an existing one.
     *
     * @throws InvalidFileUploadException|Throwable
     */
    public function fromUrl(string $url, ?Egg $egg = null): Egg
    {
        $info = pathinfo($url);
        $extension = strtolower($info['extension'] ?? 'json');

        $tmpDir = TemporaryDirectory::make()->deleteWhenDestroyed();
        $tmpPath = $tmpDir->path($info['basename']);

        $fileContents = @file_get_contents($url);

        if (!$fileContents || !file_put_contents($tmpPath, $fileContents)) {
            throw new InvalidFileUploadException('Could not download or write temporary file.');
        }

        $mime = match ($extension) {
            'yaml', 'yml' => 'application/yaml',
            default => 'application/json',
        };

        return $this->fromFile(new UploadedFile($tmpPath, $info['basename'], $mime), $egg);
    }

    /**
     * Takes an uploaded file and parses out the egg configuration from within.
     *
     * @return array<array-key, mixed>
     *
     * @throws InvalidFileUploadException
     */
    protected function parseFile(UploadedFile $file): array
    {
        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new InvalidFileUploadException('The selected file was not uploaded successfully');
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $mime = $file->getMimeType();

        try {
            $content = $file->getContent();

            $parsed = match (true) {
                in_array($extension, ['yaml', 'yml']),
                str_contains($mime, 'yaml') => Yaml::parse($content),
                default => json_decode($content, true, 512, JSON_THROW_ON_ERROR),
            };
        } catch (\JsonException|ParseException $e) {
            throw new InvalidFileUploadException('Could not read file: ' . $e->getMessage());
        }

        $version = $parsed['meta']['version'] ?? '';

        $parsed = match ($version) {
            'PTDL_v1' => $this->convertToV2($parsed),
            'PTDL_v2', 'PLCN_v1', 'PLCN_v2' => $parsed,
            default => throw new InvalidFileUploadException('The file format is not recognized.'),
        };

        if (array_get($parsed['config'], 'files')) {
            $parsed['config']['files'] = str_replace(
                array_keys(self::UPGRADE_VARIABLES),
                array_values(self::UPGRADE_VARIABLES),
                $parsed['config']['files'],
            );
        }

        [$forbidden, $allowed] = collect($parsed['variables'])
            ->map(fn ($variable) => array_merge(
                $variable,
                ['env_variable' => strtoupper($variable['env_variable'])]
            ))
            ->partition(fn ($variable) => in_array($variable['env_variable'], EggVariable::RESERVED_ENV_NAMES));

        $updatedVariables = $forbidden->map(fn ($variable) => array_merge(
            $variable,
            ['env_variable' => 'SERVER_' . $variable['env_variable']]
        ));

        if ($forbidden->count()) {
            $parsed['variables'] = $allowed->merge($updatedVariables)->all();

            if (!empty($parsed['startup'])) {
                $pattern = '/\b(' . collect($forbidden)->map(fn ($variable) => preg_quote($variable['env_variable']))->join('|') . ')\b/';
                $parsed['startup'] = preg_replace($pattern, 'SERVER_$1', $parsed['startup']) ?? $parsed['startup'];
            }
        }

        return $parsed;
    }

    /**
     * @param  array<string, mixed>  $parsed
     */
    protected function fillFromParsed(Egg $model, array $parsed): Egg
    {
        return $model->forceFill([
            'name' => Arr::get($parsed, 'name'),
            'description' => Arr::get($parsed, 'description'),
            'tags' => Arr::get($parsed, 'tags', []),
            'features' => Arr::get($parsed, 'features'),
            'docker_images' => Arr::get($parsed, 'docker_images'),
            'file_denylist' => Collection::make(Arr::get($parsed, 'file_denylist'))->filter(fn ($value) => !empty($value)),
            'update_url' => Arr::get($parsed, 'meta.update_url'),
            'config_files' => Arr::get($parsed, 'config.files'),
            'config_startup' => Arr::get($parsed, 'config.startup'),
            'config_logs' => Arr::get($parsed, 'config.logs'),
            'config_stop' => Arr::get($parsed, 'config.stop'),
            'startup' => Arr::get($parsed, 'startup'),
            'script_install' => Arr::get($parsed, 'scripts.installation.script'),
            'script_entry' => Arr::get($parsed, 'scripts.installation.entrypoint'),
            'script_container' => Arr::get($parsed, 'scripts.installation.container'),
        ]);
    }

    /**
     * @param  array<string, mixed>  $parsed
     * @return array<string, mixed>
     */
    protected function convertToV2(array $parsed): array
    {
        if (!isset($parsed['images'])) {
            $images = [Arr::get($parsed, 'image') ?? 'nil'];
        } else {
            $images = $parsed['images'];
        }

        unset($parsed['images'], $parsed['image'], $parsed['field_type']);

        $parsed['docker_images'] = [];
        foreach ($images as $image) {
            $parsed['docker_images'][$image] = $image;
        }

        return $parsed;
    }
}
