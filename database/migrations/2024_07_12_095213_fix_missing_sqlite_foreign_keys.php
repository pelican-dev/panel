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
            // api_keys_user_id_foreign
            DB::statement('ALTER TABLE api_keys RENAME TO _api_keys_old');
            DB::statement('CREATE TABLE api_keys
                ("id" integer primary key autoincrement not null,
                "token" text not null,
                "allowed_ips" text not null,
                "created_at" datetime,
                "updated_at" datetime,
                "user_id" integer not null,
                "memo" text,
                "r_servers" integer not null default \'0\',
                "r_nodes" integer not null default \'0\',
                "r_allocations" integer not null default \'0\',
                "r_users" integer not null default \'0\',
                "r_eggs" integer not null default \'0\',
                "r_database_hosts" integer not null default \'0\',
                "r_server_databases" integer not null default \'0\',
                "identifier" varchar,
                "key_type" integer not null default \'0\',
                "last_used_at" datetime,
                "expires_at" datetime,
                "r_mounts" integer not null default \'0\',
                foreign key("user_id") references "users"("id") on delete cascade)');
            DB::statement('INSERT INTO api_keys SELECT * FROM _api_keys_old');
            DB::statement('DROP TABLE _api_keys_old');
            DB::statement('CREATE UNIQUE INDEX "api_keys_identifier_unique" on "api_keys" ("identifier")');

            // database_hosts_node_id_foreign
            DB::statement('ALTER TABLE database_hosts RENAME TO _database_hosts_old');
            DB::statement('CREATE TABLE database_hosts
                ("id" integer primary key autoincrement not null,
                "name" varchar not null,
                "host" varchar not null,
                "port" integer not null,
                "username" varchar not null,
                "password" text not null,
                "max_databases" integer,
                "node_id" integer,
                "created_at" datetime,
                "updated_at" datetime,
                foreign key("node_id") references "nodes"("id") on delete set null)');
            DB::statement('INSERT INTO database_hosts SELECT * FROM _database_hosts_old');
            DB::statement('DROP TABLE _database_hosts_old');

            // mount_node_node_id_foreign
            // mount_node_mount_id_foreign
            DB::statement('ALTER TABLE mount_node RENAME TO _mount_node_old');
            DB::statement('CREATE TABLE mount_node
                ("node_id" integer not null,
                "mount_id" integer not null,
                foreign key("node_id") references "nodes"("id") on delete cascade on update cascade,
                foreign key("mount_id") references "mounts"("id") on delete cascade on update cascade)');
            DB::statement('INSERT INTO mount_node SELECT * FROM _mount_node_old');
            DB::statement('DROP TABLE _mount_node_old');
            DB::statement('CREATE UNIQUE INDEX "mount_node_node_id_mount_id_unique" on "mount_node" ("node_id", "mount_id")');

            // servers_node_id_foreign
            // servers_owner_id_foreign
            // servers_egg_id_foreign
            // servers_allocation_id_foreign
            DB::statement('ALTER TABLE servers RENAME TO _servers_old');
            DB::statement('CREATE TABLE servers
                ("id" integer primary key autoincrement not null,
                "uuid" varchar not null,
                "uuid_short" varchar not null,
                "node_id" integer not null,
                "name" varchar not null,
                "owner_id" integer not null,
                "memory" integer not null,
                "swap" integer not null,
                "disk" integer not null,
                "io" integer not null,
                "cpu" integer not null,
                "egg_id" integer not null,
                "startup" text not null,
                "created_at" datetime,
                "updated_at" datetime,
                "allocation_id" integer not null,
                "image" varchar not null,
                "description" text not null,
                "skip_scripts" tinyint(1) not null default \'0\',
                "external_id" varchar,
                "database_limit" integer default \'0\',
                "allocation_limit" integer,
                "threads" varchar,
                "backup_limit" integer not null default \'0\',
                "status" varchar,
                "installed_at" datetime,
                "oom_killer" integer not null default \'0\',
                "docker_labels" text,
                foreign key("node_id") references "nodes"("id"),
                foreign key("owner_id") references "users"("id"),
                foreign key("egg_id") references "eggs"("id"),
                foreign key("allocation_id") references "allocations"("id"))');
            DB::statement('INSERT INTO servers SELECT * FROM _servers_old');
            DB::statement('DROP TABLE _servers_old');
            DB::statement('CREATE UNIQUE INDEX "servers_allocation_id_unique" on "servers" ("allocation_id")');
            DB::statement('CREATE UNIQUE INDEX "servers_external_id_unique" on "servers" ("external_id")');
            DB::statement('CREATE UNIQUE INDEX "servers_uuid_unique" on "servers" ("uuid")');
            DB::statement('CREATE UNIQUE INDEX "servers_uuidshort_unique" on "servers" ("uuid_short")');

            // databases_server_id_foreign
            // databases_database_host_id_foreign
            DB::statement('ALTER TABLE databases RENAME TO _databases_old');
            DB::statement('CREATE TABLE databases
                ("id" integer primary key autoincrement not null,
                "server_id" integer not null,
                "database_host_id" integer not null,
                "database" varchar not null,
                "username" varchar not null,
                "remote" varchar not null default \'%\',
                "password" text not null,
                "created_at" datetime,
                "updated_at" datetime,
                "max_connections" integer default \'0\',
                foreign key("server_id") references "servers"("id"),
                foreign key("database_host_id") references "database_hosts"("id"))');
            DB::statement('INSERT INTO databases SELECT * FROM _databases_old');
            DB::statement('DROP TABLE _databases_old');
            DB::statement('CREATE UNIQUE INDEX "databases_database_host_id_server_id_database_unique" on "databases" ("database_host_id", "server_id", "database")');
            DB::statement('CREATE UNIQUE INDEX "databases_database_host_id_username_unique" on "databases" ("database_host_id", "username")');

            // allocations_node_id_foreign
            // allocations_server_id_foreign
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
                foreign key("server_id") references "servers"("id") on delete cascade on update set null)');
            DB::statement('INSERT INTO allocations SELECT * FROM _allocations_old');
            DB::statement('DROP TABLE _allocations_old');
            DB::statement('CREATE UNIQUE INDEX "allocations_node_id_ip_port_unique" on "allocations" ("node_id", "ip", "port")');

            // eggs_config_from_foreign
            // eggs_copy_script_from_foreign
            DB::statement('ALTER TABLE eggs RENAME TO _eggs_old');
            DB::statement('CREATE TABLE eggs
                ("id" integer primary key autoincrement not null,
                "name" varchar not null,
                "description" text,
                "created_at" datetime,
                "updated_at" datetime,
                "startup" text,
                "config_from" integer,
                "config_stop" varchar,
                "config_logs" text,
                "config_startup" text,
                "config_files" text,
                "script_install" text,
                "script_is_privileged" tinyint(1) not null default \'1\',
                "script_entry" varchar not null default \'ash\',
                "script_container" varchar not null default \'alpine:3.4\',
                "copy_script_from" integer,
                "uuid" varchar not null,
                "author" varchar not null,
                "features" text,
                "docker_images" text,
                "update_url" text,
                "file_denylist" text,
                "force_outgoing_ip" tinyint(1) not null default \'0\',
                "tags" text not null,
                foreign key("config_from") references "eggs"("id") on delete set null,
                foreign key("copy_script_from") references "eggs"("id") on delete set null)');
            DB::statement('INSERT INTO eggs SELECT * FROM _eggs_old');
            DB::statement('DROP TABLE _eggs_old');
            DB::statement('CREATE UNIQUE INDEX "service_options_uuid_unique" on "eggs" ("uuid")');

            // egg_mount_mount_id_foreign
            // egg_mount_egg_id_foreign
            DB::statement('ALTER TABLE egg_mount RENAME TO _egg_mount_old');
            DB::statement('CREATE TABLE egg_mount
                ("egg_id" integer not null,
                "mount_id" integer not null,
                foreign key("egg_id") references "eggs"("id") on delete cascade on update cascade,
                foreign key("mount_id") references "mounts"("id") on delete cascade on update cascade)');
            DB::statement('INSERT INTO egg_mount SELECT * FROM _egg_mount_old');
            DB::statement('DROP TABLE _egg_mount_old');
            DB::statement('CREATE UNIQUE INDEX "egg_mount_egg_id_mount_id_unique" on "egg_mount" ("egg_id", "mount_id")');

            // service_variables_egg_id_foreign
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

            // mount_server_server_id_foreign
            // mount_server_mount_id_foreign
            DB::statement('ALTER TABLE mount_server RENAME TO _mount_server_old');
            DB::statement('CREATE TABLE mount_server
                ("server_id" integer not null,
                "mount_id" integer not null,
                foreign key("server_id") references "servers"("id") on delete cascade on update cascade,
                foreign key("mount_id") references "mounts"("id") on delete cascade on update cascade)');
            DB::statement('INSERT INTO mount_server SELECT * FROM _mount_server_old');
            DB::statement('DROP TABLE _mount_server_old');
            DB::statement('CREATE UNIQUE INDEX "mount_server_server_id_mount_id_unique" on "mount_server" ("server_id", "mount_id")');

            // server_variables_variable_id_foreign
            DB::statement('ALTER TABLE server_variables RENAME TO _server_variables_old');
            DB::statement('CREATE TABLE server_variables
                ("id" integer primary key autoincrement not null,
                "server_id" integer not null,
                "variable_id" integer not null,
                "variable_value" text not null,
                "created_at" datetime,
                "updated_at" datetime,
                foreign key("server_id") references "servers"("id") on delete cascade,
                foreign key("variable_id") references "egg_variables"("id") on delete cascade)');
            DB::statement('INSERT INTO server_variables SELECT * FROM _server_variables_old');
            DB::statement('DROP TABLE _server_variables_old');

            // subusers_user_id_foreign
            // subusers_server_id_foreign
            DB::statement('ALTER TABLE subusers RENAME TO _subusers_old');
            DB::statement('CREATE TABLE subusers
                ("id" integer primary key autoincrement not null,
                "user_id" integer not null,
                "server_id" integer not null,
                "created_at" datetime,
                "updated_at" datetime,
                "permissions" text,
                foreign key("user_id") references "users"("id") on delete cascade,
                foreign key("server_id") references "servers"("id") on delete cascade)');
            DB::statement('INSERT INTO subusers SELECT * FROM _subusers_old');
            DB::statement('DROP TABLE _subusers_old');
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
