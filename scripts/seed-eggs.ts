import 'reflect-metadata';
import 'dotenv/config';
import fg from 'fast-glob';
import fs from 'node:fs/promises';
import { parse } from 'yaml';
import { MikroORM, UnderscoreNamingStrategy, Entity, PrimaryKey, Property, Unique, ManyToOne, Collection, OneToMany } from '@mikro-orm/core';
import { PostgreSqlDriver } from '@mikro-orm/postgresql';
import { MariaDbDriver } from '@mikro-orm/mariadb';
@Entity({ tableName: 'egg_variables' })
class SeedEggVariable {
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

  @ManyToOne(() => SeedEgg)
  egg!: SeedEgg;

  @Property({ type: 'date', onCreate: () => new Date() })
  createdAt: Date = new Date();

  @Property({ type: 'date', onUpdate: () => new Date() })
  updatedAt: Date = new Date();
}

// Minimal local Egg entity
@Entity({ tableName: 'eggs' })
class SeedEgg {
  @PrimaryKey({ type: 'number' })
  id!: number;

  @Property({ type: 'string' })
  @Unique()
  name!: string;

  @Property({ type: 'string', nullable: true })
  uuid?: string | null;

  @Property({ type: 'text', nullable: true })
  description?: string | null;

  @Property({ type: 'json', nullable: true })
  tags?: string[] | null;

  @Property({ type: 'json', nullable: true })
  features?: string[] | null;

  @Property({ type: 'json', nullable: true })
  dockerImages?: Record<string, string> | null;

  @Property({ type: 'string', nullable: true })
  startup?: string | null;

  // Install script fields
  @Property({ type: 'string', nullable: true })
  scriptContainer?: string | null;

  @Property({ type: 'string', nullable: true })
  scriptEntry?: string | null;

  @Property({ type: 'text', nullable: true })
  installScript?: string | null;

  // Config fields
  @Property({ type: 'json', nullable: true })
  configStartup?: Record<string, any> | null;

  @Property({ type: 'json', nullable: true })
  configFiles?: Record<string, any> | null;

  @Property({ type: 'json', nullable: true })
  configLogs?: Record<string, any> | null;

  @Property({ type: 'string', nullable: true })
  configStop?: string | null;

  @Property({ type: 'json', nullable: true })
  fileDenylist?: string[] | null;

  @OneToMany(() => SeedEggVariable, (v) => v.egg)
  variables = new Collection<SeedEggVariable>(this);

  @Property({ type: 'date', onCreate: () => new Date() })
  createdAt: Date = new Date();

  @Property({ type: 'date', onUpdate: () => new Date() })
  updatedAt: Date = new Date();
}

async function main() {
  // Build clientUrl from DB_* if DATABASE_URL is not set
  function buildUrlFromDbVars(): string | undefined {
    const conn = (process.env.DB_CONNECTION || '').toLowerCase();
    const host = process.env.DB_HOST;
    const port = process.env.DB_PORT;
    const db   = process.env.DB_DATABASE;
    const user = process.env.DB_USERNAME;
    const pass = process.env.DB_PASSWORD;
    if (!conn || !host || !port || !db || !user) return undefined;
    const scheme = conn === 'postgres' || conn === 'postgresql' ? 'postgresql' : (conn === 'mariadb' || conn === 'mysql' ? 'mariadb' : conn);
    const auth = pass ? `${encodeURIComponent(user)}:${encodeURIComponent(pass)}` : encodeURIComponent(user);
    return `${scheme}://${auth}@${host}:${port}/${db}`;
  }

  const clientUrl = process.env.DATABASE_URL ?? buildUrlFromDbVars() ?? '';
  const scheme = clientUrl.split(':', 1)[0]?.toLowerCase();
  const driver = (scheme === 'postgres' || scheme === 'postgresql') ? PostgreSqlDriver : MariaDbDriver;

  // Initialize ORM with seeder entities
  const orm = await MikroORM.init({
    driver: driver as any,
    clientUrl,
    entities: [SeedEgg, SeedEggVariable],
    namingStrategy: UnderscoreNamingStrategy,
    debug: process.env.NODE_ENV === 'development',
  } as any);
  const em = orm.em.fork();

  // Source directory with egg YAMLs copied into this repo
  const seedsRoot = `${process.cwd()}/data/eggs`;

  const files = await fg('**/*.yaml', { cwd: seedsRoot, absolute: true });
  if (files.length === 0) {
    console.warn('No egg YAML files found to seed. Checked:', seedsRoot);
    await orm.close();
    return;
  }

  let created = 0;
  let skipped = 0;

  for (const file of files) {
    try {
      const raw = await fs.readFile(file, 'utf8');
      const doc: any = parse(raw);
      const name: string | undefined = doc?.name;
      if (!name) {
        console.warn(`Skipping ${file}: missing name`);
        skipped++;
        continue;
      }
      const uuid: string | undefined = doc?.uuid;
      const description: string | undefined = doc?.description;
      const tags: string[] | undefined = doc?.tags;
      const features: string[] | undefined = doc?.features;
      const dockerImages: Record<string, string> | undefined = doc?.docker_images;
      const startup: string | undefined = doc?.startup;
      
      // Extract script fields
      const script = doc?.script;
      const scriptContainer: string | undefined = script?.container;
      const scriptEntry: string | undefined = script?.entry;
      const installScript: string | undefined = script?.install;
      
      // Extract full installation script from scripts.installation.script
      const installationScript: string | undefined = doc?.scripts?.installation?.script;
      const finalInstallScript = installationScript || installScript;
      
      // Extract container and entrypoint from scripts.installation
      const installContainer: string | undefined = doc?.scripts?.installation?.container;
      const installEntrypoint: string | undefined = doc?.scripts?.installation?.entrypoint;
      const finalContainer = installContainer || scriptContainer;
      const finalEntry = installEntrypoint || scriptEntry;
      
      // Extract config fields
      const config = doc?.config;
      const configStartup = config?.startup;
      const configFiles = config?.files;
      const configLogs = config?.logs;
      const configStop = config?.stop;
      const fileDenylist: string[] | undefined = doc?.file_denylist;
      
      // Extract variables
      const variables: Array<Record<string, any>> | undefined = doc?.variables;

      // Upsert by unique name
      let egg = await em.findOne(SeedEgg, { name });
      if (!egg) {
        egg = em.create(SeedEgg, {
          name,
          uuid: uuid ?? null,
          description: description ?? null,
          tags: tags ?? null,
          features: features ?? null,
          dockerImages: dockerImages ?? null,
          startup: startup ?? null,
          scriptContainer: finalContainer ?? null,
          scriptEntry: finalEntry ?? null,
          installScript: finalInstallScript ?? null,
          configStartup: configStartup ?? null,
          configFiles: configFiles ?? null,
          configLogs: configLogs ?? null,
          configStop: configStop ?? null,
          fileDenylist: fileDenylist ?? null,
          createdAt: new Date(),
          updatedAt: new Date(),
        } as any);
        em.persist(egg);
        
        // Create variables if they exist
        if (variables && Array.isArray(variables)) {
          for (let i = 0; i < variables.length; i++) {
            const variable = variables[i];
            const eggVariable = em.create(SeedEggVariable, {
              name: variable.name || '',
              description: variable.description || null,
              envVariable: variable.env_variable || '',
              defaultValue: variable.default_value || null,
              userViewable: variable.user_viewable ?? true,
              userEditable: variable.user_editable ?? true,
              rules: variable.rules || null,
              sort: variable.sort || i,
              egg,
              createdAt: new Date(),
              updatedAt: new Date(),
            });
            em.persist(eggVariable);
          }
        }
        
        created++;
      } else {
        // Update metadata if changed (optional upsert behavior)
        egg.uuid = uuid ?? egg.uuid ?? null;
        egg.description = description ?? egg.description ?? null;
        egg.tags = tags ?? egg.tags ?? null;
        egg.features = features ?? egg.features ?? null;
        egg.dockerImages = dockerImages ?? egg.dockerImages ?? null;
        egg.startup = startup ?? egg.startup ?? null;
        egg.scriptContainer = finalContainer ?? egg.scriptContainer ?? null;
        egg.scriptEntry = finalEntry ?? egg.scriptEntry ?? null;
        egg.installScript = finalInstallScript ?? egg.installScript ?? null;
        egg.configStartup = configStartup ?? egg.configStartup ?? null;
        egg.configFiles = configFiles ?? egg.configFiles ?? null;
        egg.configLogs = configLogs ?? egg.configLogs ?? null;
        egg.configStop = configStop ?? egg.configStop ?? null;
        egg.fileDenylist = fileDenylist ?? egg.fileDenylist ?? null;
        egg.updatedAt = new Date();
        
        // Update variables - remove existing and recreate
        await em.nativeDelete(SeedEggVariable, { egg });
        if (variables && Array.isArray(variables)) {
          for (let i = 0; i < variables.length; i++) {
            const variable = variables[i];
            const eggVariable = em.create(SeedEggVariable, {
              name: variable.name || '',
              description: variable.description || null,
              envVariable: variable.env_variable || '',
              defaultValue: variable.default_value || null,
              userViewable: variable.user_viewable ?? true,
              userEditable: variable.user_editable ?? true,
              rules: variable.rules || null,
              sort: variable.sort || i,
              egg,
              createdAt: new Date(),
              updatedAt: new Date(),
            });
            em.persist(eggVariable);
          }
        }
        
        skipped++;
      }
    } catch (err) {
      console.error('Failed to process', file, err);
    }
  }

  await em.flush();
  console.log(`Egg seeding complete. Created: ${created}, Skipped (exists or invalid): ${skipped}`);
  await orm.close();
}

main().catch((err) => {
  console.error('Seeder error:', err);
  process.exit(1);
});
