import { Entity, PrimaryKey, Property, ManyToOne } from '@mikro-orm/core';
import { User } from '../User';

@Entity({ tableName: 'authenticators' })
export class Authenticator {
  @PrimaryKey({ type: 'string' })
  credentialID!: string;

  @Property()
  providerAccountId!: string;

  @Property()
  credentialPublicKey!: string;

  @Property()
  counter!: number;

  @Property()
  credentialDeviceType!: string;

  @Property()
  credentialBackedUp!: boolean;

  @Property({ nullable: true })
  transports?: string;

  @ManyToOne(() => User, { fieldName: 'user_id', onDelete: 'cascade', primary: true })
  user!: User;
}
