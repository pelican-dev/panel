<?php

namespace App\Services\Eggs\Sharing;

use App\Models\Egg;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use App\Models\EggVariable;
use Illuminate\Database\ConnectionInterface;
use App\Services\Eggs\EggParserService;

class EggUpdateImporterService
{
    /**
     * EggUpdateImporterService constructor.
     */
    public function __construct(protected ConnectionInterface $connection, protected EggParserService $parser)
    {
    }

    /**
     * Update an existing Egg using an uploaded JSON file.
     *
     * @throws \App\Exceptions\Service\InvalidFileUploadException|\Throwable
     */
    public function handle(Egg $egg, UploadedFile $file): Egg
    {
        $fileContent = $file->get();

        $replacements = [
            // Update ips
            'server.build.env.SERVER_IP' => 'server.allocations.default.ip',
            'server.build.default.ip' => 'server.allocations.default.ip',
            // Update ports
            'server.build.env.SERVER_PORT' => 'server.allocations.default.port',
            'server.build.default.port' => 'server.allocations.default.port',
            // Update memory limits
            'server.build.env.SERVER_MEMORY' => 'server.build.memory_limit',
            'server.build.memory' => 'server.build.memory_limit',
            // Update env variables
            'server.build.env' => 'server.build.environment',
        ];

        // Replace variables
        $fileContent = str_replace(array_keys($replacements), array_values($replacements), $fileContent);

        $parsed = json_decode($fileContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \App\Exceptions\Service\InvalidFileUploadException('The uploaded file is not a valid JSON file.');
        }

        return $this->connection->transaction(function () use ($egg, $parsed) {
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
}
