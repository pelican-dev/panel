<?php

namespace App\Services\Eggs\Sharing;

use Carbon\Carbon;
use App\Models\Egg;
use Illuminate\Support\Collection;
use App\Models\EggVariable;

class EggExporterService
{
    /**
     * Return a JSON representation of an egg and its variables.
     */
    public function handle(int $egg): string
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
            'features' => $egg->features,
            'docker_images' => $egg->docker_images,
            'file_denylist' => Collection::make($egg->inherit_file_denylist)->filter(function ($value) {
                return !empty($value);
            }),
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
                    ->except(['id', 'egg_id', 'created_at', 'updated_at'])
                    ->merge(['field_type' => 'text']);
            }),
        ];

        return json_encode($struct, JSON_PRETTY_PRINT);
    }
}
