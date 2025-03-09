<?php

use App\Models\Egg;
use App\Models\EggVariable;
use App\Services\Eggs\Sharing\EggImporterService;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (Egg::all() as $egg) {
            $hasReplaced = false;
            foreach ($egg->variables as $variable) {
                if (in_array($variable->env_variable, explode(',', EggVariable::RESERVED_ENV_NAMES))) {
                    $variable->env_variable = EggImporterService::parseReservedEnvNames($variable->env_variable);
                    $hasReplaced = true;
                    DB::table('egg_variables')
                        ->where('id', $variable->id)
                        ->update(['env_variable' => $variable->env_variable]);
                }
            }
            if ($hasReplaced) {
                $egg->startup = EggImporterService::parseReservedEnvNames($egg->startup);
                $egg->script_install = EggImporterService::parseReservedEnvNames($egg->script_install);

                DB::table('eggs')
                    ->where('id', $egg->id)
                    ->update(['startup' => $egg->startup, 'script_install' => $egg->script_install]);

                foreach ($egg->servers as $server) {
                    $server->startup = EggImporterService::parseReservedEnvNames($server->startup);
                    DB::table('servers')
                        ->where('id', $server->id)
                        ->update(['startup' => $server->startup]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not Needed
    }
};
