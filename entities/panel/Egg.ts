import { Collection, Entity, OneToMany, PrimaryKey, Property, Unique } from '@mikro-orm/core';

@Entity({ tableName: 'eggs' })
export class Egg {
  @PrimaryKey({ type: 'number' })
  id!: number;

  @Property({ type: 'string' })
  @Unique()
  name!: string;

  @Property({ type: 'string', nullable: true })
  uuid?: string | null;

  @Property({ type: 'text', nullable: true })
  description?: string | null;

  @Property({ type: 'json', nullable: true })
  tags?: string[] | null;

  @Property({ type: 'json', nullable: true })
  features?: string[] | null;

  @Property({ type: 'json', nullable: true })
  dockerImages?: Record<string, string> | null;

  @Property({ type: 'text', nullable: true })
  startup?: string | null;

  // Install script fields
  @Property({ type: 'string', nullable: true })
  scriptContainer?: string | null;

  @Property({ type: 'string', nullable: true })
  scriptEntry?: string | null;

  @Property({ type: 'text', nullable: true })
  installScript?: string | null;

  // Config fields
  @Property({ type: 'json', nullable: true })
  configStartup?: Record<string, unknown> | null;

  @Property({ type: 'json', nullable: true })
  configFiles?: Record<string, unknown> | null;

  @Property({ type: 'json', nullable: true })
  configLogs?: Record<string, unknown> | null;

  @Property({ type: 'string', nullable: true })
  configStop?: string | null;

  @Property({ type: 'json', nullable: true })
  fileDenylist?: string[] | null;

  @Property({ type: 'date', onCreate: () => new Date() })
  createdAt: Date = new Date();

  @Property({ type: 'date', onUpdate: () => new Date() })
  updatedAt: Date = new Date();

  @OneToMany(() => 'EggVariable', (v: any) => v.egg, { mappedBy: 'egg' })
  variables = new Collection<any>(this);

  @OneToMany(() => 'Server', (s: any) => s.egg)
  servers = new Collection<any>(this);
}
