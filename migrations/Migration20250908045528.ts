import { Migration } from '@mikro-orm/migrations';

export class Migration20250908045528 extends Migration {

  override async up(): Promise<void> {
    this.addSql(`alter table "eggs" add column "config_startup" jsonb null, add column "config_files" jsonb null, add column "config_logs" jsonb null, add column "config_stop" varchar(255) null, add column "file_denylist" jsonb null, add column "variables" jsonb null;`);
  }

  override async down(): Promise<void> {
    this.addSql(`alter table "eggs" drop column "config_startup", drop column "config_files", drop column "config_logs", drop column "config_stop", drop column "file_denylist", drop column "variables";`);
  }

}
