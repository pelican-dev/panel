import { NextRequest, NextResponse } from 'next/server';

export async function GET(request: NextRequest) {
  try {
    const sessionCookie = request.cookies.get('pelican-session');
    
    if (!sessionCookie) {
      return NextResponse.json(
        { error: 'Not authenticated' },
        { status: 401 }
      );
    }

    // TODO: Validate session token and get user from database
    // Mock user data for now
    const user = {
      id: '1',
      email: 'admin@pelican.dev',
      name: 'Admin User',
      role: 'admin',
      permissions: ['*'],
      servers: [],
      createdAt: new Date().toISOString()
    };

    return NextResponse.json({ user });
  } catch (error) {
    console.error('Get user error:', error);
    return NextResponse.json(
      { error: 'Internal server error' },
      { status: 500 }
    );
  }
}
