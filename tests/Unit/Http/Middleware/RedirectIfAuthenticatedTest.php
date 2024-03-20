<?php

namespace App\Tests\Unit\Http\Middleware;

use Mockery as m;
use Mockery\MockInterface;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;

class RedirectIfAuthenticatedTest extends MiddlewareTestCase
{
    private MockInterface $authManager;

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->authManager = m::mock(AuthManager::class);
    }

    /**
     * Test that an authenticated user is redirected.
     */
    public function testAuthenticatedUserIsRedirected(): void
    {
        $this->authManager->shouldReceive('guard')->with(null)->once()->andReturnSelf();
        $this->authManager->shouldReceive('check')->withNoArgs()->once()->andReturn(true);

        $response = $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('index'), $response->getTargetUrl());
    }

    /**
     * Test that a non-authenticated user continues through the middleware.
     */
    public function testNonAuthenticatedUserIsNotRedirected(): void
    {
        $this->authManager->shouldReceive('guard')->with(null)->once()->andReturnSelf();
        $this->authManager->shouldReceive('check')->withNoArgs()->once()->andReturn(false);

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Return an instance of the middleware using mocked dependencies.
     */
    private function getMiddleware(): RedirectIfAuthenticated
    {
        return new RedirectIfAuthenticated($this->authManager);
    }
}
