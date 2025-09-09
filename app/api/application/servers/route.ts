import { NextRequest, NextResponse } from 'next/server';
import { randomUUID } from 'crypto';
import { getEm } from '@/lib/orm';
import { Server } from '@/entities/panel/Server';
import { User } from '@/entities/User';
import { Node } from '@/entities/panel/Node';
import { Egg } from '@/entities/panel/Egg';

export async function GET(request: NextRequest) {
  try {
    const { searchParams } = new URL(request.url);
    const page = Math.max(1, parseInt(searchParams.get('page') || '1', 10));
    const perPage = Math.max(1, parseInt(searchParams.get('per_page') || '50', 10));
    const status = searchParams.get('status');

    const em = await getEm();

    const where: Record<string, unknown> = {};
    if (status) where.status = status;

    const [servers, total] = await em.findAndCount(Server, where, {
      populate: ['allocation', 'allocations', 'node', 'egg', 'user'],
      limit: perPage,
      offset: (page - 1) * perPage,
      orderBy: { id: 'asc' },
    });

    const totalPages = Math.max(1, Math.ceil(total / perPage));

    const data = servers.map((s) => {
      const e = s as any;
      return ({
      object: 'server',
      attributes: {
        id: e.id,
        external_id: null,
        uuid: e.uuid,
        identifier: e.identifier,
        name: e.name,
        description: e.description,
        status: e.status,
        suspended: e.suspended,
        limits: {
          memory: e.memoryMb,
          swap: e.swapMb,
          disk: e.diskMb,
          io: e.io,
          cpu: e.cpuPct,
          threads: e.threads,
          oom_disabled: e.oomDisabled,
          oom_killer: e.oomKiller,
        },
        feature_limits: {
          databases: e.databases,
          allocations: e.allocationsLimit,
          backups: e.backups,
        },
        user: e.user?.id ?? null,
        node: e.node?.id ?? null,
        allocation: e.allocation?.id ?? undefined,
        allocations: e.allocations.map((a: any) => ({
          id: a.id,
          ip: a.ip,
          port: a.port,
          alias: a.alias ?? undefined,
          is_default: a.isDefault,
        })),
        egg: e.egg?.id ?? null,
        container: {
          startup_command: '',
          image: '',
          installed: 0,
          environment: {},
        },
        updated_at: e.updatedAt.toISOString(),
        created_at: e.createdAt.toISOString(),
      },
    });
    });

    return NextResponse.json({
      object: 'list',
      data,
      meta: {
        pagination: {
          total,
          count: servers.length,
          per_page: perPage,
          current_page: page,
          total_pages: totalPages,
          links: {},
        },
      },
    });
  } catch (error) {
    console.error('Get servers error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

export async function POST(request: NextRequest) {
  try {
    // TODO: Check authentication and permissions (disabled for now)
    const { name, description, eggId, memoryMb, diskMb, cpuPct, userId, nodeId } = await request.json();

    if (!name || !userId || !nodeId) {
      return NextResponse.json({ error: 'name, userId, and nodeId are required' }, { status: 400 });
    }

    const em = await getEm();

    // Generate uuid/identifier
    const uuid = randomUUID();
    const identifier = uuid.replace(/-/g, '').slice(0, 8);

    const server = new Server();
    server.uuid = uuid;
    server.identifier = identifier;
    server.name = name;
    server.description = description ?? '';
    server.status = 'installing';
    server.suspended = false;
    server.memoryMb = memoryMb ?? 1024;
    server.swapMb = 0;
    server.diskMb = diskMb ?? 5120;
    server.io = 500;
    server.cpuPct = cpuPct ?? 100;
    server.threads = '';
    server.oomDisabled = true;
    server.oomKiller = false;
    server.databases = 0;
    server.allocationsLimit = 1;
    server.backups = 0;
    (server as any).user = em.getReference(User, userId as string);
    (server as any).node = em.getReference(Node, Number(nodeId));
    (server as any).egg = eggId ? em.getReference(Egg, Number(eggId)) : null;

    await em.persistAndFlush(server);

    return NextResponse.json({
      object: 'server',
      attributes: {
        id: server.id,
        external_id: null,
        uuid: server.uuid,
        identifier: server.identifier,
        name: server.name,
        description: server.description,
        status: server.status,
        suspended: server.suspended,
        limits: {
          memory: server.memoryMb,
          swap: server.swapMb,
          disk: server.diskMb,
          io: server.io,
          cpu: server.cpuPct,
          threads: server.threads,
          oom_disabled: server.oomDisabled,
          oom_killer: server.oomKiller,
        },
        feature_limits: {
          databases: server.databases,
          allocations: server.allocationsLimit,
          backups: server.backups,
        },
        user: (server as any).user.id,
        node: (server as any).node.id,
        allocation: server.allocation?.id ?? undefined,
        allocations: [],
        egg: server.egg?.id ?? null,
        container: { startup_command: '', image: '', installed: 0, environment: {} },
        updated_at: server.updatedAt.toISOString(),
        created_at: server.createdAt.toISOString(),
      },
    }, { status: 201 });
  } catch (error) {
    console.error('Create server error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
