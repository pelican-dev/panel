import { Migration } from '@mikro-orm/migrations';

export class Migration20250908004914 extends Migration {

  override async up(): Promise<void> {
    this.addSql(`alter table "eggs" alter column "startup" type text using ("startup"::text);`);
  }

  override async down(): Promise<void> {
    this.addSql(`alter table "eggs" alter column "startup" type varchar(255) using ("startup"::varchar(255));`);
  }

}
