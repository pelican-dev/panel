<?php

namespace App\Services\Eggs\Sharing;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Arr;
use App\Models\Egg;
use Illuminate\Http\UploadedFile;
use App\Models\EggVariable;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Str;
use App\Services\Eggs\EggParserService;

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
    public function handle(UploadedFile $file): Egg
    {
        $parsed = $this->parser->handle($file);

        $replacements = [
            'server.build.env.SERVER_IP' => 'server.allocations.default.ip',
            'server.build.default.ip' => 'server.allocations.default.ip',
            'server.build.env.SERVER_PORT' => 'server.allocations.default.port',
            'server.build.default.port' => 'server.allocations.default.port',
            'server.build.env.SERVER_MEMORY' => 'server.build.memory_limit',
            'server.build.memory' => 'server.build.memory_limit',
            'server.build.env' => 'server.build.environment',
        ];

        $parsed = $this->recursiveStringReplace($parsed, $replacements);

        return $this->connection->transaction(function () use ($parsed) {
            $uuid = $parsed['uuid'] ?? Uuid::uuid4()->toString();
            $egg = Egg::where('uuid', $uuid)->first() ?? new Egg();

            $egg = $egg->forceFill([
                'uuid' => $uuid,
                'author' => Arr::get($parsed, 'author'),
                'copy_script_from' => null,
            ]);

            $egg = $this->parser->fillFromParsed($egg, $parsed);
            $egg->save();

            foreach ($parsed['variables'] ?? [] as $variable) {
                EggVariable::query()->forceCreate(array_merge($variable, ['egg_id' => $egg->id]));
            }

            return $egg;
        });
    }

    protected function recursiveStringReplace(array $data, array $replacements): array
    {
        return array_map(function ($value) use ($replacements) {
            if (is_array($value)) {
                return $this->recursiveStringReplace($value, $replacements);
            } elseif (is_string($value)) {
                return Str::replace(array_keys($replacements), array_values($replacements), $value);
            }

            return $value;
        }, $data);
    }
}
