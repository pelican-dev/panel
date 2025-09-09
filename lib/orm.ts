import 'reflect-metadata';
import { MikroORM, EntityManager } from '@mikro-orm/core';
import { PostgreSqlDriver } from '@mikro-orm/postgresql';
import { Options } from '@mikro-orm/core';
import config from '../mikro-orm.config';

// Keep a singleton across HMR in dev
const globalForOrm = globalThis as unknown as {
  orm?: MikroORM<PostgreSqlDriver>;
};

export async function getORM() {
  if (!globalForOrm.orm) {
    globalForOrm.orm = await MikroORM.init<PostgreSqlDriver>(config as Options<PostgreSqlDriver>);
  }
  return globalForOrm.orm;
}

export async function getEm(): Promise<EntityManager<PostgreSqlDriver>> {
  const orm = await getORM();
  // Fork a new EntityManager to keep request-local context
  return orm.em.fork() as EntityManager<PostgreSqlDriver>;
}
