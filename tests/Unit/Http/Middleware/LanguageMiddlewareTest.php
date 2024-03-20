<?php

namespace App\Tests\Unit\Http\Middleware;

use Mockery as m;
use Mockery\MockInterface;
use App\Models\User;
use Illuminate\Foundation\Application;
use App\Http\Middleware\LanguageMiddleware;

class LanguageMiddlewareTest extends MiddlewareTestCase
{
    private MockInterface $appMock;

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->appMock = m::mock(Application::class);
    }

    /**
     * Test that a language is defined via the middleware for guests.
     */
    public function testLanguageIsSetForGuest(): void
    {
        $this->request->shouldReceive('user')->withNoArgs()->andReturnNull();
        $this->appMock->shouldReceive('setLocale')->with('en')->once()->andReturnNull();

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Test that a language is defined via the middleware for a user.
     */
    public function testLanguageIsSetWithAuthenticatedUser(): void
    {
        $user = User::factory()->make(['language' => 'de']);

        $this->request->shouldReceive('user')->withNoArgs()->andReturn($user);
        $this->appMock->shouldReceive('setLocale')->with('de')->once()->andReturnNull();

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Return an instance of the middleware using mocked dependencies.
     */
    private function getMiddleware(): LanguageMiddleware
    {
        return new LanguageMiddleware($this->appMock);
    }
}
