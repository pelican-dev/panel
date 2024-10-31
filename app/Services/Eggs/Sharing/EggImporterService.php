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

    public function __construct(protected ConnectionInterface $connection)
    {
    }

    /**
     * Take an uploaded JSON file and parse it into a new egg.
     *
     * @throws \App\Exceptions\Service\InvalidFileUploadException|\Throwable
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

            // Don't check for this anymore
            for ($i = 0; $i < count($parsed['variables']); $i++) {
                unset($parsed['variables'][$i]['field_type']);
            }

            $egg = $this->fillFromParsed($egg, $parsed);
            $egg->save();

            // Update existing variables or create new ones.
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
     * Take an url and parse it into a new egg or update an existing one.
     *
     * @throws \App\Exceptions\Service\InvalidFileUploadException|\Throwable
     */
    public function fromUrl(string $url, ?Egg $egg = null): Egg
    {
        $info = pathinfo($url);
        $tmpDir = TemporaryDirectory::make()->deleteWhenDestroyed();
        $tmpPath = $tmpDir->path($info['basename']);

        file_put_contents($tmpPath, file_get_contents($url));

        return $this->fromFile(new UploadedFile($tmpPath, $info['basename'], 'application/json'), $egg);
    }

    /**
     * Takes an uploaded file and parses out the egg configuration from within.
     *
     * @throws \JsonException
     * @throws \App\Exceptions\Service\InvalidFileUploadException
     */
    protected function parseFile(UploadedFile $file): array
    {
        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new InvalidFileUploadException('The selected file was not uploaded successfully');
        }

        $parsed = json_decode($file->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $version = $parsed['meta']['version'] ?? '';

        $parsed = match ($version) {
            'PTDL_v1' => $this->convertToV2($parsed),
            'PTDL_v2' => $parsed,
            'PLCN_V1' => $parsed,
            default => throw new InvalidFileUploadException('The JSON file provided is not in a format that can be recognized.')
        };

        // Make sure we only use recent variable format from now on
        $parsed['config']['files'] = str_replace(
            array_keys(self::UPGRADE_VARIABLES),
            array_values(self::UPGRADE_VARIABLES),
            $parsed['config']['files'] ?? '',
        );

        return $parsed;
    }

    /**
     * Fills the provided model with the parsed JSON data.
     */
    protected function fillFromParsed(Egg $model, array $parsed): Egg
    {
        return $model->forceFill([
            'name' => Arr::get($parsed, 'name'),
            'description' => Arr::get($parsed, 'description'),
            'features' => Arr::get($parsed, 'features'),
            'docker_images' => Arr::get($parsed, 'docker_images'),
            'file_denylist' => Collection::make(Arr::get($parsed, 'file_denylist'))
                ->filter(fn ($value) => !empty($value)),
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
     * Converts a PTDL_V1 egg into the expected PTDL_V2 egg format. This just handles
     * the "docker_images" field potentially not being present, and not being in the
     * expected "key => value" format.
     */
    protected function convertToV2(array $parsed): array
    {
        // Maintain backwards compatability for eggs that are still using the old single image
        // string format. New eggs can provide an array of Docker images that can be used.
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
