import { Entity, PrimaryKey, Property } from '@mikro-orm/core';

@Entity({ tableName: 'verification_tokens' })
export class VerificationToken {
  @PrimaryKey({ type: 'string' })
  identifier!: string;

  @PrimaryKey({ type: 'string' })
  token!: string;

  @Property()
  expires!: Date;
}
