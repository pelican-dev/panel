<?php

namespace App\Services\Eggs\Sharing;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Arr;
use App\Models\Egg;
use Illuminate\Http\UploadedFile;
use App\Models\EggVariable;
use Illuminate\Database\ConnectionInterface;
use App\Services\Eggs\EggParserService;
use Illuminate\Support\Collection;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class EggImporterService
{
    public function __construct(protected ConnectionInterface $connection, protected EggParserService $parser)
    {
    }

    /**
     * Take an uploaded JSON file and parse it into a new egg.
     *
     * @throws \App\Exceptions\Service\InvalidFileUploadException|\Throwable
     */
    public function fromFile(UploadedFile $file, Egg $egg = null): Egg
    {
        $parsed = $this->parser->handle($file);

        return $this->connection->transaction(function () use ($egg, $parsed) {
            $uuid = $parsed['uuid'] ?? Uuid::uuid4()->toString();
            $egg = $egg ?? Egg::where('uuid', $uuid)->first() ?? new Egg();

            $egg = $egg->forceFill([
                'uuid' => $uuid,
                'author' => Arr::get($parsed, 'author'),
                'copy_script_from' => null,
            ]);

            $egg = $this->parser->fillFromParsed($egg, $parsed);
            $egg->save();

            // Update existing variables or create new ones.
            foreach ($parsed['variables'] ?? [] as $variable) {
                EggVariable::unguarded(function () use ($egg, $variable) {
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
    public function fromUrl(string $url, Egg $egg = null): Egg
    {
        $info = pathinfo($url);
        $tmpDir = TemporaryDirectory::make()->deleteWhenDestroyed();
        $tmpPath = $tmpDir->path($info['basename']);

        file_put_contents($tmpPath, file_get_contents($url));

        return $this->fromFile(new UploadedFile($tmpPath, $info['basename'], 'application/json'), $egg);
    }
}
