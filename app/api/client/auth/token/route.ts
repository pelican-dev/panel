import { NextRequest, NextResponse } from 'next/server';

export async function POST(request: NextRequest) {
  try {
    const { serverId, serverToken } = await request.json();

    if (!serverId || !serverToken) {
      return NextResponse.json(
        { error: 'Server ID and token are required' },
        { status: 400 }
      );
    }

    // TODO: Validate server token against database
    // Mock validation for now
    if (serverToken === 'mock-server-token') {
      const apiKey = `pelican_${serverId}_${Date.now()}`;
      
      // TODO: Store API key in database with expiration
      
      return NextResponse.json({
        success: true,
        apiKey,
        expiresAt: new Date(Date.now() + 24 * 60 * 60 * 1000).toISOString(), // 24 hours
        serverId
      });
    }

    return NextResponse.json(
      { error: 'Invalid server token' },
      { status: 401 }
    );

  } catch (error) {
    console.error('Token generation error:', error);
    return NextResponse.json(
      { error: 'Internal server error' },
      { status: 500 }
    );
  }
}
