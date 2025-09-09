import { Entity, PrimaryKey, Property, Unique } from '@mikro-orm/core';

@Entity({ tableName: 'users' })
export class User {
  @PrimaryKey({ type: 'string' })
  id: string;

  @Property({ nullable: true })
  name?: string;

  @Property()
  @Unique()
  email!: string;

  @Property({ nullable: true })
  emailVerified?: Date;

  @Property({ nullable: true })
  image?: string;

  @Property({ nullable: true })
  @Unique()
  username?: string | null;

  @Property({ onCreate: () => new Date() })
  createdAt: Date = new Date();

  @Property({ onUpdate: () => new Date() })
  updatedAt: Date = new Date();

  constructor(id: string) {
    this.id = id;
  }
}
