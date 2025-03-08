<?php

use App\Models\Egg;
use App\Models\EggVariable;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $reservedEnvNames = explode(',', EggVariable::RESERVED_ENV_NAMES);
        $pattern = '/\b(' . implode('|', array_map('preg_quote', $reservedEnvNames)) . ')\b/';

        foreach (Egg::all() as $egg) {
            $hasReplaced = false;
            foreach ($egg->variables as $variable) {
                if (in_array($variable->env_variable, $reservedEnvNames)) {
                    $variable->env_variable = preg_replace($pattern, 'SERVER_$1', $variable->env_variable);
                    $hasReplaced = true;
                    DB::table('egg_variables')
                        ->where('id', $variable->id)
                        ->update(['env_variable' => $variable->env_variable]);
                }
            }
            if ($hasReplaced) {
                $egg->startup = preg_replace($pattern, 'SERVER_$1', $egg->startup);
                $egg->script_install = preg_replace($pattern, 'SERVER_$1', $egg->script_install);

                DB::table('eggs')
                    ->where('id', $egg->id)
                    ->update(['startup' => $egg->startup, 'script_install' => $egg->script_install]);

                foreach ($egg->servers as $server) {
                    $server->startup = preg_replace($pattern, 'SERVER_$1', $server->startup);
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
