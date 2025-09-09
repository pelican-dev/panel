import { NextRequest, NextResponse } from 'next/server';
import { getEm } from '@/lib/orm';
import { Egg } from '@/entities/panel/Egg';

export async function GET(_: NextRequest, ctx: { params: Promise<{ uuid: string }> }) {
  try {
    const { uuid } = await ctx.params;
    const em = await getEm();
    
    // Try to find egg by uuid, numeric id, or name
    let egg = await em.findOne(Egg, { uuid }, { populate: ['variables'] });
    if (!egg) {
      const maybeId = Number(uuid);
      if (!Number.isNaN(maybeId)) {
        egg = await em.findOne(Egg, { id: maybeId }, { populate: ['variables'] });
      }
    }
    if (!egg) {
      egg = await em.findOne(Egg, { name: uuid }, { populate: ['variables'] });
    }

    if (!egg) {
      return NextResponse.json({ error: 'Egg not found' }, { status: 404 });
    }

    const dockerImages = egg.dockerImages ?? {};
    const docker_image = dockerImages
      ? dockerImages[Object.keys(dockerImages)[0]]
      : null;

    const config = {
      files: egg.configFiles ?? {},
      startup: egg.configStartup ?? {},
      stop: egg.configStop ?? '',
      logs: egg.configLogs ?? [],
      file_denylist: egg.fileDenylist ?? [],
      extends: null,
    };

    const script = {
      container: egg.scriptContainer ?? null,
      entry: egg.scriptEntry ?? null,
      install: egg.installScript ?? null,
    };

    const payload = {
      object: 'egg',
      attributes: {
        id: egg.id,
        uuid: egg.uuid ?? null,
        name: egg.name,
        author: null,
        description: egg.description ?? null,
        tags: egg.tags ?? [],
        features: egg.features ?? [],
        docker_image,
        docker_images: dockerImages,
        config,
        startup: egg.startup ?? null,
        script,
        variables: egg.variables.getItems().map(v => ({
          name: v.name,
          description: v.description,
          env_variable: v.envVariable,
          default_value: v.defaultValue,
          user_viewable: v.userViewable,
          user_editable: v.userEditable,
          rules: v.rules ?? [],
          sort: v.sort,
        })),
        created_at: egg.createdAt ? egg.createdAt.toISOString() : null,
        updated_at: egg.updatedAt ? egg.updatedAt.toISOString() : null,
      },
    };

    return NextResponse.json(payload);
  } catch (error) {
    console.error('Get egg by uuid error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

export async function PUT(request: NextRequest, ctx: { params: Promise<{ uuid: string }> }) {
  try {
    const body = await request.json();
    const attrs = (body?.attributes ?? body ?? {}) as Record<string, unknown>;

    const em = await getEm();
    const { uuid } = await ctx.params;
    let egg = await em.findOne(Egg, { uuid });
    if (!egg && typeof attrs.name === 'string') {
      egg = await em.findOne(Egg, { name: attrs.name as string });
    }

    if (!egg) {
      return NextResponse.json({ error: 'Egg not found' }, { status: 404 });
    }

    if (typeof attrs.name === 'string') egg.name = attrs.name as string;
    if (typeof attrs.uuid === 'string') egg.uuid = attrs.uuid as string;
    if (typeof attrs.description === 'string') egg.description = attrs.description as string;
    if (Array.isArray(attrs.tags)) egg.tags = attrs.tags as string[];
    if (Array.isArray(attrs.features)) egg.features = attrs.features as string[];
    if (attrs.docker_images && typeof attrs.docker_images === 'object') egg.dockerImages = attrs.docker_images as Record<string, string>;
    if (typeof attrs.startup === 'string') egg.startup = attrs.startup as string;
    // Persist script fields if provided
    const script = attrs.script as Record<string, unknown> | undefined;
    if (script && typeof script === 'object') {
      if (typeof script.container === 'string') egg.scriptContainer = script.container as string;
      if (typeof script.entry === 'string') egg.scriptEntry = script.entry as string;
      if (typeof script.install === 'string') egg.installScript = script.install as string;
    }
    egg.updatedAt = new Date();

    await em.flush();

    return NextResponse.json({
      object: 'egg',
      attributes: {
        id: egg.id,
        uuid: egg.uuid ?? null,
        name: egg.name,
        description: egg.description ?? null,
        features: egg.features ?? [],
        docker_images: egg.dockerImages ?? {},
        startup: egg.startup ?? null,
        created_at: egg.createdAt?.toISOString?.() ?? null,
        updated_at: egg.updatedAt?.toISOString?.() ?? null,
      },
    });
  } catch (error) {
    console.error('Update egg error:', error);
    return NextResponse.json({ error: 'Invalid request' }, { status: 400 });
  }
}

export async function DELETE(_: NextRequest, ctx: { params: Promise<{ uuid: string }> }) {
  try {
    const em = await getEm();
    const { uuid } = await ctx.params;

    // Try by uuid first
    let egg = await em.findOne(Egg, { uuid });

    // If param is numeric, try by id
    if (!egg) {
      const maybeId = Number(uuid);
      if (!Number.isNaN(maybeId)) {
        egg = await em.findOne(Egg, { id: maybeId });
      }
    }

    // Fallback by name
    if (!egg) {
      egg = await em.findOne(Egg, { name: uuid });
    }

    if (!egg) {
      return NextResponse.json({ error: 'Egg not found' }, { status: 404 });
    }

    await em.removeAndFlush(egg);
    return new NextResponse(null, { status: 204 });
  } catch (error) {
    console.error('Delete egg error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
