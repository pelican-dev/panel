import { NextRequest, NextResponse } from 'next/server';

export async function POST(
  request: NextRequest,
  { params }: { params: { id: string } }
) {
  try {
    const { 
      players,
      performance,
      events,
      apiKey 
    } = await request.json();

    if (!apiKey) {
      return NextResponse.json({ error: 'API key required' }, { status: 401 });
    }

    const serverId = params.id;
    
    // TODO: Validate server exists and API key matches
    // TODO: Store statistics in time-series database for monitoring
    
    console.log(`Stats from server ${serverId}:`, {
      players: {
        online: players?.online || 0,
        peak: players?.peak || 0,
        joins: players?.joins || 0,
        leaves: players?.leaves || 0
      },
      performance: {
        tps: performance?.tps || 0,
        mspt: performance?.mspt || 0,
        cpu: performance?.cpu || 0,
        memory: performance?.memory || 0
      },
      events: events || [],
      timestamp: new Date().toISOString()
    });

    return NextResponse.json({
      success: true,
      message: 'Statistics recorded',
      serverId
    });

  } catch (error) {
    console.error('Stats error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
