import { NextRequest, NextResponse } from 'next/server';

export async function POST(request: NextRequest) {
  try {
    const { email, password } = await request.json();

    // TODO: Implement actual authentication logic
    // For now, this is a mock implementation
    if (!email || !password) {
      return NextResponse.json(
        { error: 'Email and password are required' },
        { status: 400 }
      );
    }

    // Mock authentication - replace with real logic
    if (email === 'admin@pelican.dev' && password === 'admin') {
      const user = {
        id: '1',
        email: 'admin@pelican.dev',
        name: 'Admin User',
        role: 'admin',
        permissions: ['*']
      };

      // TODO: Generate JWT token and set secure cookies
      const response = NextResponse.json({ 
        success: true, 
        user,
        message: 'Login successful' 
      });
      
      // Set session cookie (mock)
      response.cookies.set('pelican-session', 'mock-session-token', {
        httpOnly: true,
        secure: process.env.NODE_ENV === 'production',
        maxAge: 24 * 60 * 60, // 24 hours
        path: '/'
      });

      return response;
    }

    return NextResponse.json(
      { error: 'Invalid credentials' },
      { status: 401 }
    );

  } catch (error) {
    console.error('Login error:', error);
    return NextResponse.json(
      { error: 'Internal server error' },
      { status: 500 }
    );
  }
}
