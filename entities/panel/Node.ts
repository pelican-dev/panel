import { Collection, Entity, OneToMany, PrimaryKey, Property, Unique } from '@mikro-orm/core';

@Entity({ tableName: 'nodes' })
export class Node {
  @PrimaryKey()
  id!: number;

  @Property()
  @Unique()
  name!: string;

  @Property()
  address!: string;

  @Property({ default: true })
  ssl: boolean = true;

  @Property({ default: true })
  public: boolean = true;

  @Property({ onCreate: () => new Date() })
  createdAt: Date = new Date();

  @Property({ onUpdate: () => new Date() })
  updatedAt: Date = new Date();

  @OneToMany(() => 'Server', (s: any) => s.node)
  servers = new Collection<any>(this);
}
