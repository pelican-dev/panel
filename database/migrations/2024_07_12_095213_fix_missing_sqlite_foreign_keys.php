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
        // Only needed for sqlite
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            return;
        }

        DB::transaction(function () {
            // service_variables_egg_id_foreign
            DB::statement('ALTER TABLE egg_variables RENAME TO _egg_variables_old');
            DB::statement('CREATE TABLE IF NOT EXISTS egg_variables 
                ("id" integer primary key autoincrement not null, 
                "egg_id" integer not null, 
                "name" varchar not null, 
                "description" text not null, 
                "env_variable" varchar not null, 
                "default_value" text not null, 
                "user_viewable" integer not null, 
                "user_editable" integer not null, 
                "rules" text not null, 
                "created_at" datetime, 
                "updated_at" datetime, "sort" integer, 
                foreign key("egg_id") references "eggs"("id") on delete cascade)');
            DB::statement('INSERT INTO egg_variables SELECT * FROM _egg_variables_old');
            DB::statement('DROP TABLE _egg_variables_old');

            // server_variables_variable_id_foreign
            DB::statement('ALTER TABLE server_variables RENAME TO _server_variables_old');
            DB::statement('CREATE TABLE server_variables 
                ("id" integer primary key autoincrement not null, 
                "server_id" integer not null, 
                "variable_id" integer not null, 
                "variable_value" text not null, 
                "created_at" datetime, 
                "updated_at" datetime, 
                foreign key("variable_id") references "egg_variables"("id") on delete cascade)');
            DB::statement('INSERT INTO server_variables SELECT * FROM _server_variables_old');
            DB::statement('DROP TABLE _server_variables_old');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse not needed
    }
};
