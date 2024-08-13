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
            DB::statement('ALTER TABLE allocations RENAME TO _allocations_old');
            DB::statement('CREATE TABLE allocations
                ("id" integer primary key autoincrement not null,
                "node_id" integer not null,
                "ip" varchar not null,
                "port" integer not null,
                "server_id" integer,
                "created_at" datetime,
                "updated_at" datetime,
                "ip_alias" text,
                "notes" varchar,
                foreign key("node_id") references "nodes"("id") on delete cascade,
                foreign key("server_id") references "servers"("id") on delete set null)');
            DB::statement('INSERT INTO allocations SELECT * FROM _allocations_old');
            DB::statement('DROP TABLE _allocations_old');
            DB::statement('CREATE UNIQUE INDEX "allocations_node_id_ip_port_unique" on "allocations" ("node_id", "ip", "port")');
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
