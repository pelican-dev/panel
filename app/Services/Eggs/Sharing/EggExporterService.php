<?php

namespace App\Services\Eggs\Sharing;

use App\Enums\EggFormat;
use Carbon\Carbon;
use App\Models\Egg;
use Illuminate\Support\Collection;
use App\Models\EggVariable;
use Symfony\Component\Yaml\Yaml;

class EggExporterService
{
    /**
     * Return a JSON or YAML representation of an egg and its variables.
     */
    public function handle(int $egg, EggFormat $format): string
    {
        $egg = Egg::with(['scriptFrom', 'configFrom', 'variables'])->findOrFail($egg);

        $struct = [
            '_comment' => 'DO NOT EDIT: FILE GENERATED AUTOMATICALLY BY PANEL',
            'meta' => [
                'version' => Egg::EXPORT_VERSION,
                'update_url' => $egg->update_url,
            ],
            'exported_at' => Carbon::now()->toAtomString(),
            'name' => $egg->name,
            'author' => $egg->author,
            'uuid' => $egg->uuid,
            'description' => $egg->description,
            'tags' => $egg->tags,
            'features' => $egg->features,
            'docker_images' => $egg->docker_images,
            'file_denylist' => Collection::make($egg->inherit_file_denylist)->filter(fn ($v) => !empty($v))->values(),
            'startup' => $egg->startup,
            'config' => [
                'files' => $egg->inherit_config_files,
                'startup' => $egg->inherit_config_startup,
                'logs' => $egg->inherit_config_logs,
                'stop' => $egg->inherit_config_stop,
            ],
            'scripts' => [
                'installation' => [
                    'script' => $egg->copy_script_install,
                    'container' => $egg->copy_script_container,
                    'entrypoint' => $egg->copy_script_entry,
                ],
            ],
            'variables' => $egg->variables->map(function (EggVariable $eggVariable) {
                return Collection::make($eggVariable->toArray())
                    ->except(['id', 'egg_id', 'created_at', 'updated_at']);
            })->values()->toArray(),
        ];

        return match ($format) {
            EggFormat::JSON => json_encode($struct, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            EggFormat::YAML => Yaml::dump($this->yamlExport($struct), 10, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK | Yaml::DUMP_OBJECT_AS_MAP),
        };
    }

    protected function yamlExport(mixed $data): mixed
    {
        if ($data instanceof Collection) {
            $data = $data->all();
        }

        if (is_string($data)) {
            $decoded = json_decode($data, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $this->yamlExport($decoded);
            }

            return str_replace(["\r\n", '\\r\\n', '\\n'], "\n", $data);
        }

        if (is_array($data)) {
            $result = [];

            foreach ($data as $key => $value) {
                if (
                    is_string($value) &&
                    strtolower($key) === 'description' &&
                    (str_contains($value, "\n") || strlen($value) > 80)
                ) {
                    $value = wordwrap($value, 100, "\n");
                } else {
                    $value = $this->yamlExport($value);
                }

                $result[$key] = $value;
            }

            return $result;
        }

        return $data;
    }
}
