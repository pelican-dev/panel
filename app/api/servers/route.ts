import { NextRequest, NextResponse } from 'next/server';

// Mock server data - replace with database
const mockServers = [
  {
    id: 'srv_1',
    name: 'Minecraft Survival',
    type: 'minecraft',
    status: 'running',
    players: { current: 12, max: 50 },
    resources: {
      cpu: 45.2,
      memory: { used: 2048, total: 4096 },
      disk: { used: 1024, total: 10240 }
    },
    port: 25565,
    version: '1.20.4',
    createdAt: '2024-01-15T10:30:00Z',
    lastSeen: new Date().toISOString()
  },
  {
    id: 'srv_2',
    name: 'Discord Bot',
    type: 'discord',
    status: 'stopped',
    players: { current: 0, max: 0 },
    resources: {
      cpu: 0,
      memory: { used: 0, total: 512 },
      disk: { used: 256, total: 1024 }
    },
    port: null,
    version: '2.0.1',
    createdAt: '2024-02-01T14:20:00Z',
    lastSeen: '2024-02-28T18:45:00Z'
  }
];

export async function GET(request: NextRequest) {
  try {
    // TODO: Check authentication
    const sessionCookie = request.cookies.get('pelican-session');
    if (!sessionCookie) {
      return NextResponse.json({ error: 'Not authenticated' }, { status: 401 });
    }

    const { searchParams } = new URL(request.url);
    const type = searchParams.get('type');
    const status = searchParams.get('status');

    let servers = [...mockServers];

    // Filter by type if specified
    if (type) {
      servers = servers.filter(server => server.type === type);
    }

    // Filter by status if specified
    if (status) {
      servers = servers.filter(server => server.status === status);
    }

    return NextResponse.json({ servers });
  } catch (error) {
    console.error('Get servers error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

export async function POST(request: NextRequest) {
  try {
    // TODO: Check authentication and permissions
    const sessionCookie = request.cookies.get('pelican-session');
    if (!sessionCookie) {
      return NextResponse.json({ error: 'Not authenticated' }, { status: 401 });
    }

    const { name, type, version, memory, port } = await request.json();

    if (!name || !type) {
      return NextResponse.json(
        { error: 'Name and type are required' },
        { status: 400 }
      );
    }

    // TODO: Create server in database and initialize container
    const newServer = {
      id: `srv_${Date.now()}`,
      name,
      type,
      status: 'creating',
      players: { current: 0, max: type === 'minecraft' ? 20 : 0 },
      resources: {
        cpu: 0,
        memory: { used: 0, total: memory || 1024 },
        disk: { used: 0, total: 5120 }
      },
      port: port || null,
      version: version || 'latest',
      createdAt: new Date().toISOString(),
      lastSeen: new Date().toISOString()
    };

    mockServers.push(newServer);

    return NextResponse.json({ 
      success: true, 
      server: newServer,
      message: 'Server created successfully' 
    }, { status: 201 });

  } catch (error) {
    console.error('Create server error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
