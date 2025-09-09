import { Entity, PrimaryKey, Property, ManyToOne, Unique } from '@mikro-orm/core';
import { User } from '../User';

@Entity({ tableName: 'sessions' })
export class Session {
  @PrimaryKey({ type: 'string' })
  @Unique()
  sessionToken!: string;

  @Property()
  expires!: Date;

  @Property({ onCreate: () => new Date() })
  createdAt: Date = new Date();

  @Property({ onUpdate: () => new Date() })
  updatedAt: Date = new Date();

  @ManyToOne(() => User, { onDelete: 'cascade' })
  user!: User;
}
