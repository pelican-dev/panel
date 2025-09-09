import { Migration } from '@mikro-orm/migrations';

export class Migration20250907234339 extends Migration {

  override async up(): Promise<void> {
    this.addSql(`create table "eggs" ("id" serial primary key, "name" varchar(255) not null, "created_at" timestamptz not null, "updated_at" timestamptz not null);`);
    this.addSql(`alter table "eggs" add constraint "eggs_name_unique" unique ("name");`);

    this.addSql(`create table "nodes" ("id" serial primary key, "name" varchar(255) not null, "address" varchar(255) not null, "ssl" boolean not null default true, "public" boolean not null default true, "created_at" timestamptz not null, "updated_at" timestamptz not null);`);
    this.addSql(`alter table "nodes" add constraint "nodes_name_unique" unique ("name");`);

    this.addSql(`create table "users" ("id" varchar(255) not null, "name" varchar(255) null, "email" varchar(255) not null, "email_verified" timestamptz null, "image" varchar(255) null, "username" varchar(255) null, "created_at" timestamptz not null, "updated_at" timestamptz not null, constraint "users_pkey" primary key ("id"));`);
    this.addSql(`alter table "users" add constraint "users_email_unique" unique ("email");`);
    this.addSql(`alter table "users" add constraint "users_username_unique" unique ("username");`);

    this.addSql(`create table "sessions" ("session_token" varchar(255) not null, "expires" timestamptz not null, "created_at" timestamptz not null, "updated_at" timestamptz not null, "user_id" varchar(255) not null, constraint "sessions_pkey" primary key ("session_token"));`);

    this.addSql(`create table "servers" ("id" serial primary key, "uuid" varchar(255) not null, "identifier" varchar(255) not null, "name" varchar(255) not null, "description" varchar(255) not null default '', "status" varchar(255) not null default 'offline', "suspended" boolean not null default false, "memory_mb" int not null default 1024, "swap_mb" int not null default 0, "disk_mb" int not null default 10240, "io" int not null default 500, "cpu_pct" int not null default 100, "threads" varchar(255) not null default '', "oom_disabled" boolean not null default false, "oom_killer" boolean not null default false, "databases" int not null default 0, "allocations_limit" int not null default 1, "backups" int not null default 0, "user_id" varchar(255) not null, "node_id" int not null, "egg_id" int null, "allocation_id" int null, "created_at" timestamptz not null, "updated_at" timestamptz not null);`);
    this.addSql(`alter table "servers" add constraint "servers_uuid_unique" unique ("uuid");`);
    this.addSql(`alter table "servers" add constraint "servers_identifier_unique" unique ("identifier");`);
    this.addSql(`alter table "servers" add constraint "servers_allocation_id_unique" unique ("allocation_id");`);

    this.addSql(`create table "allocations" ("id" serial primary key, "ip" varchar(255) not null, "port" int not null, "alias" varchar(255) null, "is_default" boolean not null default false, "server_id" int not null, "created_at" timestamptz not null, "updated_at" timestamptz not null);`);
    this.addSql(`alter table "allocations" add constraint "allocations_ip_port_unique" unique ("ip", "port");`);

    this.addSql(`create table "authenticators" ("credential_id" varchar(255) not null, "user_id" varchar(255) not null, "provider_account_id" varchar(255) not null, "credential_public_key" varchar(255) not null, "counter" int not null, "credential_device_type" varchar(255) not null, "credential_backed_up" boolean not null, "transports" varchar(255) null, constraint "authenticators_pkey" primary key ("credential_id", "user_id"));`);

    this.addSql(`create table "accounts" ("provider" varchar(255) not null, "provider_account_id" varchar(255) not null, "type" varchar(255) not null, "refresh_token" varchar(255) null, "access_token" varchar(255) null, "expires_at" int null, "token_type" varchar(255) null, "scope" varchar(255) null, "id_token" varchar(255) null, "session_state" varchar(255) null, "created_at" timestamptz not null, "updated_at" timestamptz not null, "user_id" varchar(255) not null, constraint "accounts_pkey" primary key ("provider", "provider_account_id"));`);

    this.addSql(`create table "verification_tokens" ("identifier" varchar(255) not null, "token" varchar(255) not null, "expires" timestamptz not null, constraint "verification_tokens_pkey" primary key ("identifier", "token"));`);

    this.addSql(`alter table "sessions" add constraint "sessions_user_id_foreign" foreign key ("user_id") references "users" ("id") on update cascade;`);

    this.addSql(`alter table "servers" add constraint "servers_user_id_foreign" foreign key ("user_id") references "users" ("id") on update cascade;`);
    this.addSql(`alter table "servers" add constraint "servers_node_id_foreign" foreign key ("node_id") references "nodes" ("id") on update cascade;`);
    this.addSql(`alter table "servers" add constraint "servers_egg_id_foreign" foreign key ("egg_id") references "eggs" ("id") on update cascade on delete set null;`);
    this.addSql(`alter table "servers" add constraint "servers_allocation_id_foreign" foreign key ("allocation_id") references "allocations" ("id") on update cascade on delete set null;`);

    this.addSql(`alter table "allocations" add constraint "allocations_server_id_foreign" foreign key ("server_id") references "servers" ("id") on update cascade;`);

    this.addSql(`alter table "authenticators" add constraint "authenticators_user_id_foreign" foreign key ("user_id") references "users" ("id") on update cascade;`);

    this.addSql(`alter table "accounts" add constraint "accounts_user_id_foreign" foreign key ("user_id") references "users" ("id") on update cascade;`);
  }

}
