import { Entity, PrimaryKey, Property, ManyToOne } from '@mikro-orm/core';
import { User } from '../User';

@Entity({ tableName: 'accounts' })
export class Account {
  @PrimaryKey({ type: 'string' })
  provider!: string;

  @PrimaryKey({ type: 'string' })
  providerAccountId!: string;

  @Property()
  type!: string;

  @Property({ nullable: true })
  refresh_token?: string;

  @Property({ nullable: true })
  access_token?: string;

  @Property({ nullable: true })
  expires_at?: number;

  @Property({ nullable: true })
  token_type?: string;

  @Property({ nullable: true })
  scope?: string;

  @Property({ nullable: true })
  id_token?: string;

  @Property({ nullable: true })
  session_state?: string;

  @Property({ onCreate: () => new Date() })
  createdAt: Date = new Date();

  @Property({ onUpdate: () => new Date() })
  updatedAt: Date = new Date();

  @ManyToOne(() => User, { onDelete: 'cascade' })
  user!: User;
}
