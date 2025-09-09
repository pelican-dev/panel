import { Entity, ManyToOne, PrimaryKey, Property } from '@mikro-orm/core';

@Entity({ tableName: 'egg_variables' })
export class EggVariable {
  @PrimaryKey({ type: 'number' })
  id!: number;

  @Property({ type: 'string' })
  name!: string;

  @Property({ type: 'text', nullable: true })
  description?: string | null;

  @Property({ type: 'string' })
  envVariable!: string;

  @Property({ type: 'string', nullable: true })
  defaultValue?: string | null;

  @Property({ type: 'boolean', default: true })
  userViewable: boolean = true;

  @Property({ type: 'boolean', default: true })
  userEditable: boolean = true;

  @Property({ type: 'json', nullable: true })
  rules?: string[] | null;

  @Property({ type: 'number', default: 0 })
  sort: number = 0;

  @ManyToOne(() => 'Egg', { inversedBy: 'variables' })
  egg!: any;

  @Property({ type: 'date', onCreate: () => new Date() })
  createdAt: Date = new Date();

  @Property({ type: 'date', onUpdate: () => new Date() })
  updatedAt: Date = new Date();
}
