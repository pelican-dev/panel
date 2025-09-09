import { Migration } from '@mikro-orm/migrations';

export class Migration20250908050151 extends Migration {

  override async up(): Promise<void> {
    this.addSql(`create table "egg_variables" ("id" serial primary key, "name" varchar(255) not null, "description" text null, "env_variable" varchar(255) not null, "default_value" varchar(255) null, "user_viewable" boolean not null default true, "user_editable" boolean not null default true, "rules" jsonb null, "sort" int not null default 0, "egg_id" int not null, "created_at" date not null, "updated_at" date not null);`);

    this.addSql(`alter table "egg_variables" add constraint "egg_variables_egg_id_foreign" foreign key ("egg_id") references "eggs" ("id") on update cascade;`);

    this.addSql(`alter table "eggs" drop column "variables";`);
  }

  override async down(): Promise<void> {
    this.addSql(`drop table if exists "egg_variables" cascade;`);

    this.addSql(`alter table "eggs" add column "variables" jsonb null;`);
  }

}
