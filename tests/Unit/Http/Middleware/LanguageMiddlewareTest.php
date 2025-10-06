<?php

namespace App\Tests\Unit\Http\Middleware;

use App\Http\Middleware\LanguageMiddleware;
use App\Models\User;
use Illuminate\Foundation\Application;
use Mockery as m;
use Mockery\MockInterface;

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
    public function test_language_is_set_for_guest(): void
    {
        $this->request->shouldReceive('user')->withNoArgs()->andReturnNull();
        $this->appMock->shouldReceive('setLocale')->with('en')->once()->andReturnNull();

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Test that a language is defined via the middleware for a user.
     */
    public function test_language_is_set_with_authenticated_user(): void
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
