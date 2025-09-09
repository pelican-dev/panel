import { Entity, PrimaryKey, Property, ManyToOne, Unique } from '@mikro-orm/core';

@Entity({ tableName: 'allocations' })
@Unique({ properties: ['ip', 'port'] })
export class Allocation {
  @PrimaryKey()
  id!: number;

  @Property()
  ip!: string;

  @Property()
  port!: number;

  @Property({ nullable: true })
  alias?: string;

  @Property({ default: false })
  isDefault: boolean = false;

  @ManyToOne(() => 'Server')
  server!: any;

  @Property({ onCreate: () => new Date() })
  createdAt: Date = new Date();

  @Property({ onUpdate: () => new Date() })
  updatedAt: Date = new Date();
}
