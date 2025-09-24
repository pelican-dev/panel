<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::table('eggs')->update([
                'config_startup' => DB::raw("config_startup::jsonb - 'userInteraction'"),
            ]);

            return;
        }

        // Remove User Interaction from startup config
        DB::table('eggs')->update([
            'config_startup' => DB::raw('JSON_REMOVE(config_startup, \'$.userInteraction\')'),
        ]);
    }

    public function down(): void
    {
        // Add blank User Interaction array back to startup config
        DB::table('eggs')->update([
            'config_startup' => DB::raw('JSON_SET(config_startup, \'$.userInteraction\', JSON_ARRAY())'),
        ]);
    }
};
