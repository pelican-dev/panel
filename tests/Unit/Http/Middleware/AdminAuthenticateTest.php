<?php

namespace App\Tests\Unit\Http\Middleware;

use App\Models\User;
use App\Http\Middleware\AdminAuthenticate;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AdminAuthenticateTest extends MiddlewareTestCase
{
    /**
     * Test that an admin is authenticated.
     */
    public function testAdminsAreAuthenticated(): void
    {
        $user = User::factory()->make(['root_admin' => 1]);

        $this->request->shouldReceive('user')->withNoArgs()->twice()->andReturn($user);

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Test that a missing user in the request triggers an error.
     */
    public function testExceptionIsThrownIfUserDoesNotExist(): void
    {
        $this->expectException(AccessDeniedHttpException::class);

        $this->request->shouldReceive('user')->withNoArgs()->once()->andReturnNull();

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Test that an exception is thrown if the user is not an admin.
     */
    public function testExceptionIsThrownIfUserIsNotAnAdmin(): void
    {
        $this->expectException(AccessDeniedHttpException::class);

        $user = User::factory()->make(['root_admin' => 0]);

        $this->request->shouldReceive('user')->withNoArgs()->twice()->andReturn($user);

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Return an instance of the middleware using mocked dependencies.
     */
    private function getMiddleware(): AdminAuthenticate
    {
        return new AdminAuthenticate();
    }
}
