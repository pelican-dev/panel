import { NextRequest, NextResponse } from 'next/server';

export async function POST(
  request: NextRequest,
  { params }: { params: { id: string } }
) {
  try {
    const { 
      logs,
      apiKey 
    } = await request.json();

    if (!apiKey) {
      return NextResponse.json({ error: 'API key required' }, { status: 401 });
    }

    if (!logs || !Array.isArray(logs)) {
      return NextResponse.json({ error: 'Logs array required' }, { status: 400 });
    }

    const serverId = params.id;
    
    // TODO: Validate server exists and API key matches
    // TODO: Store logs in database/logging system
    
    console.log(`Logs from server ${serverId}:`, {
      logCount: logs.length,
      logs: logs.slice(0, 5), // Show first 5 for debugging
      timestamp: new Date().toISOString()
    });

    return NextResponse.json({
      success: true,
      message: 'Logs received',
      serverId,
      processed: logs.length
    });

  } catch (error) {
    console.error('Log submission error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}

export async function GET(
  request: NextRequest,
  { params }: { params: { id: string } }
) {
  try {
    const { searchParams } = new URL(request.url);
    const apiKey = searchParams.get('apiKey');
    
    if (!apiKey) {
      return NextResponse.json({ error: 'API key required' }, { status: 401 });
    }

    // TODO: Validate server API key
    // TODO: Return recent logs for the server
    
    const mockLogs = [
      {
        timestamp: new Date().toISOString(),
        level: 'INFO',
        message: 'Server is running normally',
        source: 'system'
      }
    ];

    return NextResponse.json({
      logs: mockLogs,
      serverId: params.id
    });

  } catch (error) {
    console.error('Get logs error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
