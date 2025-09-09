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
  }
];

export async function GET(
  request: NextRequest,
  { params }: { params: { id: string } }
) {
  try {
    const sessionCookie = request.cookies.get('pelican-session');
    if (!sessionCookie) {
      return NextResponse.json({ error: 'Not authenticated' }, { status: 401 });
    }

    const server = mockServers.find(s => s.id === params.id);
    if (!server) {
      return NextResponse.json({ error: 'Server not found' }, { status: 404 });
    }

    return NextResponse.json({ server });
  } catch (error) {
    console.error('Get server error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

export async function PATCH(
  request: NextRequest,
  { params }: { params: { id: string } }
) {
  try {
    const sessionCookie = request.cookies.get('pelican-session');
    if (!sessionCookie) {
      return NextResponse.json({ error: 'Not authenticated' }, { status: 401 });
    }

    const serverIndex = mockServers.findIndex(s => s.id === params.id);
    if (serverIndex === -1) {
      return NextResponse.json({ error: 'Server not found' }, { status: 404 });
    }

    const updates = await request.json();
    mockServers[serverIndex] = { ...mockServers[serverIndex], ...updates };

    return NextResponse.json({ 
      success: true, 
      server: mockServers[serverIndex],
      message: 'Server updated successfully' 
    });
  } catch (error) {
    console.error('Update server error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

export async function DELETE(
  request: NextRequest,
  { params }: { params: { id: string } }
) {
  try {
    const sessionCookie = request.cookies.get('pelican-session');
    if (!sessionCookie) {
      return NextResponse.json({ error: 'Not authenticated' }, { status: 401 });
    }

    const serverIndex = mockServers.findIndex(s => s.id === params.id);
    if (serverIndex === -1) {
      return NextResponse.json({ error: 'Server not found' }, { status: 404 });
    }

    // TODO: Stop container and cleanup resources
    mockServers.splice(serverIndex, 1);

    return NextResponse.json({ 
      success: true,
      message: 'Server deleted successfully' 
    });
  } catch (error) {
    console.error('Delete server error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
