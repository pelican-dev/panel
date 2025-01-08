<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove User Interaction from startup config
        switch (Schema::getConnection()->getDriverName()) {
            case 'sqlite':
            case 'mysql':
            case 'mariadb':
                DB::table('eggs')->update([
                    'config_startup' => DB::raw('JSON_REMOVE(config_startup, \'$.userInteraction\')'),
                ]);
                break;
            case 'pgsql':
                DB::table('eggs')->update([
                    'config_startup' => DB::raw('(config_startup::jsonb - \'userInteraction\')::text'),
                ]);
                break;
        }
    }

    public function down(): void
    {
        // Add blank User Interaction array back to startup config
        switch (Schema::getConnection()->getDriverName()) {
            case 'sqlite':
            case 'mysql':
            case 'mariadb':
                DB::table('eggs')->update([
                    'config_startup' => DB::raw('JSON_SET(config_startup, \'$.userInteraction\', JSON_ARRAY())'),
                ]);
                break;
            case 'pgsql':
                DB::table('eggs')->update([
                    'config_startup' => DB::raw('jsonb_set(config_startup::jsonb, \'{userInteraction}\', \'[]\'::jsonb)::text'),
                ]);
                break;
        }
    }
};
