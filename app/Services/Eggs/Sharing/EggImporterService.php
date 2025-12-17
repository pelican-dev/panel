<?php

namespace App\Services\Eggs\Sharing;

use App\Enums\EggFormat;
use App\Exceptions\Service\InvalidFileUploadException;
use App\Models\Egg;
use App\Models\EggVariable;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use JsonException;
use Ramsey\Uuid\Uuid;
use stdClass;
use Symfony\Component\Yaml\Yaml;
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
     * Take a JSON or YAML as string and parse it into a new egg.
     *
     * @throws InvalidFileUploadException|Throwable
     */
    public function fromContent(string $content, EggFormat $format = EggFormat::YAML, ?Egg $egg = null): Egg
    {
        $parsed = $this->parse($content, $format);

        return $this->fromParsed($parsed, $egg);
    }

    /**
     * Take an uploaded JSON or YAML file and parse it into a new egg.
     *
     * @throws InvalidFileUploadException|Throwable
     */
    public function fromFile(UploadedFile $file, ?Egg $egg = null): Egg
    {
        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new InvalidFileUploadException('The selected file was not uploaded successfully');
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $mime = $file->getMimeType();

        try {
            $content = $file->getContent();

            if (in_array($extension, ['yaml', 'yml']) || str_contains($mime, 'yaml')) {
                return $this->fromContent($content, EggFormat::YAML, $egg);
            }

            return $this->fromContent($content, EggFormat::JSON, $egg);
        } catch (Throwable $e) {
            throw new InvalidFileUploadException('File parse failed: ' . $e->getMessage());
        }
    }

    /**
     * Take a URL (YAML or JSON) and parse it into a new egg or update an existing one.
     *
     * @throws InvalidFileUploadException|Throwable
     */
    public function fromUrl(string $url, ?Egg $egg = null): Egg
    {
        $info = pathinfo($url);
        $extension = strtolower($info['extension']);

        $format = match ($extension) {
            'yaml', 'yml' => EggFormat::YAML,
            'json' => EggFormat::JSON,
            default => throw new InvalidFileUploadException('Unsupported file format.'),
        };

        $content = Http::timeout(5)->connectTimeout(1)->get($url)->throw()->body();

        return $this->fromContent($content, $format, $egg);
    }

    /**
     * Take an array and parse it into a new egg.
     *
     * @param  array<array-key, mixed>  $parsed
     *
     * @throws InvalidFileUploadException|Throwable
     */
    protected function fromParsed(array $parsed, ?Egg $egg = null): Egg
    {
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
     * Takes a string and parses out the egg configuration from within.
     *
     * @return array<array-key, mixed>
     *
     * @throws InvalidFileUploadException|JsonException
     */
    protected function parse(string $content, EggFormat $format): array
    {
        try {
            $parsed = match ($format) {
                EggFormat::YAML => Yaml::parse($content),
                default => json_decode($content, true, 512, JSON_THROW_ON_ERROR),
            };
        } catch (Throwable $e) {
            throw new InvalidFileUploadException('File parse failed: ' . $e->getMessage());
        }

        $version = $parsed['meta']['version'] ?? '';

        $parsed = match ($version) {
            'PTDL_v1' => $this->convertToV3($this->convertLegacy($parsed)),
            'PTDL_v2', 'PLCN_v1', 'PLCN_v2' => $this->convertToV3($parsed),
            Egg::EXPORT_VERSION => $parsed,
            default => throw new InvalidFileUploadException('The file format is not recognized.'),
        };

        if (isset($parsed['config']) && (is_array($parsed['config']) || $parsed['config'] instanceof stdClass)) {
            $parsed['config'] = (array) $parsed['config'];
            foreach ($parsed['config'] as $key => $value) {
                if (is_array($value) || $value instanceof stdClass) {
                    $parsed['config'][$key] = json_encode((array) $value, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
                }

                if ($key === 'files' && is_string($parsed['config'][$key])) {
                    $parsed['config'][$key] = str_replace(
                        array_keys(self::UPGRADE_VARIABLES),
                        array_values(self::UPGRADE_VARIABLES),
                        $parsed['config'][$key]
                    );
                }
            }
        }

        if (isset($parsed['scripts']['installation']) && (is_array($parsed['scripts']['installation']) || $parsed['scripts']['installation'] instanceof stdClass)) {
            $parsed['scripts']['installation'] = (array) $parsed['scripts']['installation'];
            foreach ($parsed['scripts']['installation'] as $key => $value) {
                if (is_array($value) || $value instanceof stdClass) {
                    $parsed['scripts']['installation'][$key] = json_encode((array) $value, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
                }
            }
        }

        // Reserved env var name handling
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

            foreach ($parsed['startup_commands'] ?? [] as $name => $startup) {
                $pattern = '/\b(' . collect($forbidden)->map(fn ($variable) => preg_quote($variable['env_variable']))->join('|') . ')\b/';
                $parsed['startup_commands'][$name] = preg_replace($pattern, 'SERVER_$1', $startup) ?? $startup;
            }
        }

        return $parsed;
    }

    /**
     * @param  array<string, mixed>  $parsed
     */
    protected function fillFromParsed(Egg $model, array $parsed): Egg
    {
        // Handle image data if present
        if (!empty($parsed['image']) && str_starts_with($parsed['image'], 'data:')) {
            $this->saveEggImageFromBase64($parsed['image'], $model);
        }

        return $model->forceFill([
            'name' => Arr::get($parsed, 'name'),
            'description' => Arr::get($parsed, 'description'),
            'tags' => Arr::get($parsed, 'tags', []),
            'features' => Arr::get($parsed, 'features'),
            'docker_images' => Arr::get($parsed, 'docker_images'),
            'file_denylist' => Collection::make(Arr::get($parsed, 'file_denylist'))->filter(fn ($value) => !empty($value)),
            'update_url' => Arr::get($parsed, 'meta.update_url'),
            'config_files' => json_encode(json_decode(Arr::get($parsed, 'config.files')), JSON_PRETTY_PRINT),
            'config_startup' => json_encode(json_decode(Arr::get($parsed, 'config.startup')), JSON_PRETTY_PRINT),
            'config_logs' => json_encode(json_decode(Arr::get($parsed, 'config.logs')), JSON_PRETTY_PRINT),
            'config_stop' => Arr::get($parsed, 'config.stop'),
            'startup_commands' => Arr::get($parsed, 'startup_commands'),
            'script_install' => Arr::get($parsed, 'scripts.installation.script'),
            'script_entry' => Arr::get($parsed, 'scripts.installation.entrypoint'),
            'script_container' => Arr::get($parsed, 'scripts.installation.container'),
        ]);
    }

    /**
     * Save an egg image from base64 data to a file.
     */
    private function saveEggImageFromBase64(string $base64String, Egg $egg): void
    {
        if (!preg_match('/^data:image\/([\w+]+);base64,(.+)$/', $base64String, $matches)) {
            return;
        }

        $extension = $matches[1];
        $data = base64_decode($matches[2]);

        if (!$data) {
            return;
        }

        $normalizedExtension = match ($extension) {
            'svg+xml' => 'svg',
            'jpeg' => 'jpg',
            default => $extension,
        };

        Storage::disk('public')->put(Egg::ICON_STORAGE_PATH . "/$egg->uuid.$normalizedExtension", $data);
    }

    /**
     * @param  array<string, mixed>  $parsed
     * @return array<string, mixed>
     */
    protected function convertLegacy(array $parsed): array
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

    /**
     * @param  array<string, mixed>  $parsed
     * @return array<string, mixed>
     */
    protected function convertToV3(array $parsed): array
    {
        $startup = $parsed['startup'];

        unset($parsed['startup']);

        $parsed['startup_commands'] = [
            'Default' => $startup,
        ];

        return $parsed;
    }
}
