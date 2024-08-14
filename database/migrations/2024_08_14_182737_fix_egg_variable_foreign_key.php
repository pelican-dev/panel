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

        // Disable foreign checks
        // legacy_alter_table needs to be 'ON' so existing foreign key table references aren't renamed when renaming the table, see https://www.sqlite.org/lang_altertable.html
        DB::statement('PRAGMA foreign_keys = OFF');
        DB::statement('PRAGMA legacy_alter_table = ON');

        DB::transaction(function () {
            DB::statement('ALTER TABLE egg_variables RENAME TO _egg_variables_old');
            DB::statement('CREATE TABLE egg_variables 
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
                "updated_at" datetime, 
                "sort" integer, 
                foreign key("egg_id") references "eggs"("id") on delete cascade)');
            DB::statement('INSERT INTO egg_variables SELECT * FROM _egg_variables_old');
            DB::statement('DROP TABLE _egg_variables_old');
            DB::statement('CREATE UNIQUE INDEX "egg_variables_env_variable_unique" on "egg_variables" ("egg_id", "env_variable")');
            DB::statement('CREATE UNIQUE INDEX "egg_variables_name_unique" on "egg_variables" ("egg_id", "name")');
        });

        DB::statement('PRAGMA foreign_keys = ON');
        DB::statement('PRAGMA legacy_alter_table = OFF');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse not needed
    }
};
