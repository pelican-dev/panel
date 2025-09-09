import { NextRequest, NextResponse } from 'next/server';

// Mock log entries - replace with real server logs
const mockLogs = [
  {
    timestamp: new Date(Date.now() - 60000).toISOString(),
    level: 'INFO',
    message: '[Server thread/INFO] [minecraft/DedicatedServer]: Starting minecraft server version 1.20.4',
    source: 'server'
  },
  {
    timestamp: new Date(Date.now() - 45000).toISOString(),
    level: 'INFO',
    message: '[Server thread/INFO] [minecraft/DedicatedServer]: Loading properties',
    source: 'server'
  },
  {
    timestamp: new Date(Date.now() - 30000).toISOString(),
    level: 'INFO',
    message: '[Server thread/INFO] [minecraft/DedicatedServer]: Done (22.315s)! For help, type "help"',
    source: 'server'
  },
  {
    timestamp: new Date(Date.now() - 15000).toISOString(),
    level: 'INFO',
    message: '[User Authenticator #1/INFO] [minecraft/DedicatedServer]: UUID of player TestUser is 12345678-1234-1234-1234-123456789abc',
    source: 'server'
  },
  {
    timestamp: new Date(Date.now() - 5000).toISOString(),
    level: 'INFO',
    message: '[Server thread/INFO] [minecraft/PlayerList]: TestUser joined the game',
    source: 'server'
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

    const { searchParams } = new URL(request.url);
    const lines = parseInt(searchParams.get('lines') || '50');
    const level = searchParams.get('level');

    let logs = [...mockLogs];

    // Filter by log level if specified
    if (level) {
      logs = logs.filter(log => log.level.toLowerCase() === level.toLowerCase());
    }

    // Limit number of lines
    logs = logs.slice(-lines);

    return NextResponse.json({ 
      logs,
      serverId: params.id,
      totalLines: logs.length
    });

  } catch (error) {
    console.error('Get logs error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
