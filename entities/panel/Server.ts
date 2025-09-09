import { Collection, Entity, PrimaryKey, Property, ManyToOne, OneToMany, OneToOne, Unique } from '@mikro-orm/core';

@Entity({ tableName: 'servers' })
export class Server {
  @PrimaryKey()
  id!: number;

  @Property()
  @Unique()
  uuid!: string;

  @Property()
  @Unique()
  identifier!: string;

  @Property()
  name!: string;

  @Property({ default: '' })
  description: string = '';

  @Property({ default: 'offline' })
  status: string = 'offline';

  @Property({ default: false })
  suspended: boolean = false;

  // Limits
  @Property({ default: 1024 })
  memoryMb: number = 1024;
  @Property({ default: 0 })
  swapMb: number = 0;
  @Property({ default: 10240 })
  diskMb: number = 10240;
  @Property({ default: 500 })
  io: number = 500;
  @Property({ default: 100 })
  cpuPct: number = 100;
  @Property({ default: '' })
  threads: string = '';
  @Property({ default: false })
  oomDisabled: boolean = false;
  @Property({ default: false })
  oomKiller: boolean = false;

  // Feature limits
  @Property({ default: 0 })
  databases: number = 0;
  @Property({ default: 1 })
  allocationsLimit: number = 1;
  @Property({ default: 0 })
  backups: number = 0;

  // Relations
  @ManyToOne(() => 'User')
  user!: unknown;

  @ManyToOne(() => 'Node')
  node!: unknown;

  @ManyToOne(() => 'Egg', { nullable: true })
  egg?: unknown | null;

  @OneToOne({ entity: () => 'Allocation', nullable: true, owner: true, fieldName: 'allocation_id' })
  allocation?: unknown | null;

  @OneToMany(() => 'Allocation', (a: any) => a.server)
  allocations = new Collection<any>(this);

  @Property({ onCreate: () => new Date() })
  createdAt: Date = new Date();

  @Property({ onUpdate: () => new Date() })
  updatedAt: Date = new Date();
}
