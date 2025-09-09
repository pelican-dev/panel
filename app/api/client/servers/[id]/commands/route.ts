import { NextRequest, NextResponse } from 'next/server';

export async function POST(
  request: NextRequest,
  { params }: { params: { id: string } }
) {
  try {
    const { 
      command,
      player,
      apiKey 
    } = await request.json();

    if (!apiKey) {
      return NextResponse.json({ error: 'API key required' }, { status: 401 });
    }

    if (!command) {
      return NextResponse.json({ error: 'Command is required' }, { status: 400 });
    }

    const serverId = params.id;
    
    // TODO: Validate server exists and API key matches
    // TODO: Queue command for server execution
    // TODO: Log command execution for audit trail
    
    console.log(`Command for server ${serverId}:`, {
      command,
      player: player || 'CONSOLE',
      timestamp: new Date().toISOString()
    });

    return NextResponse.json({
      success: true,
      message: 'Command queued for execution',
      serverId,
      command,
      player: player || 'CONSOLE'
    });

  } catch (error) {
    console.error('Command execution error:', error);
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
    // TODO: Return pending commands for the server
    
    const mockCommands = [
      {
        id: 'cmd_1',
        command: 'say Welcome to the server!',
        player: 'CONSOLE',
        status: 'pending',
        createdAt: new Date().toISOString()
      }
    ];

    return NextResponse.json({
      commands: mockCommands,
      serverId: params.id
    });

  } catch (error) {
    console.error('Get commands error:', error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
