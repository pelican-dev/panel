import { Migration } from '@mikro-orm/migrations';

export class Migration20250908031500 extends Migration {
  override async up(): Promise<void> {
    this.addSql(`alter table "eggs" add column if not exists "script_container" varchar(255) null;`);
    this.addSql(`alter table "eggs" add column if not exists "script_entry" varchar(255) null;`);
    this.addSql(`alter table "eggs" add column if not exists "install_script" text null;`);
  }

  override async down(): Promise<void> {
    this.addSql(`alter table "eggs" drop column if exists "script_container";`);
    this.addSql(`alter table "eggs" drop column if exists "script_entry";`);
    this.addSql(`alter table "eggs" drop column if exists "install_script";`);
  }
}
