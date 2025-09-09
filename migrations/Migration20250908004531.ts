import { Migration } from '@mikro-orm/migrations';

export class Migration20250908004531 extends Migration {

  override async up(): Promise<void> {
    this.addSql(`alter table "eggs" add column "uuid" varchar(255) null, add column "description" text null, add column "tags" jsonb null, add column "features" jsonb null, add column "docker_images" jsonb null, add column "startup" varchar(255) null;`);
    this.addSql(`alter table "eggs" alter column "created_at" type date using ("created_at"::date);`);
    this.addSql(`alter table "eggs" alter column "updated_at" type date using ("updated_at"::date);`);
  }

  override async down(): Promise<void> {
    this.addSql(`alter table "eggs" drop column "uuid", drop column "description", drop column "tags", drop column "features", drop column "docker_images", drop column "startup";`);

    this.addSql(`alter table "eggs" alter column "created_at" type timestamptz using ("created_at"::timestamptz);`);
    this.addSql(`alter table "eggs" alter column "updated_at" type timestamptz using ("updated_at"::timestamptz);`);
  }

}
