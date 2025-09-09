import 'reflect-metadata';
import dotenv from 'dotenv';
import dotenvExpand from 'dotenv-expand';
dotenvExpand.expand(dotenv.config());

import { defineConfig, UnderscoreNamingStrategy, type Options } from '@mikro-orm/core';
import { PostgreSqlDriver } from '@mikro-orm/postgresql';
import { MariaDbDriver } from '@mikro-orm/mariadb';
// Explicit entity class registration (avoids file-glob discovery issues in Next.js)
import { User } from './entities/User';
import { Server } from './entities/panel/Server';
import { Allocation } from './entities/panel/Allocation';
import { Egg } from './entities/panel/Egg';
import { EggVariable } from './entities/panel/EggVariable';
import { Node } from './entities/panel/Node';
import { Account } from './entities/auth/Account';
import { Session } from './entities/auth/Session';
import { Authenticator } from './entities/auth/Authenticator';
import { VerificationToken } from './entities/auth/VerificationToken';

function buildUrlFromDbVars(): string | undefined {
  const conn = (process.env.DB_CONNECTION || '').toLowerCase();
  const host = process.env.DB_HOST;
  const port = process.env.DB_PORT;
  const db   = process.env.DB_DATABASE;
  const user = process.env.DB_USERNAME;
  const pass = process.env.DB_PASSWORD;

  if (!conn || !host || !port || !db || !user) return undefined;

  const scheme =
    conn === 'postgres' || conn === 'postgresql' ? 'postgresql' :
    conn === 'mariadb' || conn === 'mysql' ? 'mariadb' : conn;

  const auth = pass ? `${encodeURIComponent(user)}:${encodeURIComponent(pass)}` : encodeURIComponent(user);
  return `${scheme}://${auth}@${host}:${port}/${db}`;
}

const urlFromDb = buildUrlFromDbVars();
const clientUrl = process.env.DATABASE_URL ?? urlFromDb ?? '';

function pickDriver() {
  const scheme = clientUrl.split(':', 1)[0]?.toLowerCase();
  if (scheme === 'postgres' || scheme === 'postgresql') return PostgreSqlDriver;
  if (scheme === 'mysql' || scheme === 'mariadb') return MariaDbDriver;
  throw new Error(`Unsupported or missing DB scheme. Set DB_CONNECTION or DATABASE_URL. Got: ${clientUrl}`);
}

export default defineConfig({
  driver: pickDriver(),
  clientUrl,
  entities: [
    User,
    Server,
    Allocation,
    Egg,
    EggVariable,
    Node,
    Account,
    Session,
    Authenticator,
    VerificationToken,
  ],
  namingStrategy: UnderscoreNamingStrategy,
  debug: process.env.NODE_ENV === 'development',
  migrations: {
    path: './migrations',
    pathTs: './migrations',
    tableName: 'mikro_orm_migrations',
  },
} as Options);
