<?php

namespace App\Tests\Unit\Http\Middleware\Api\Application;

use App\Tests\Unit\Http\Middleware\MiddlewareTestCase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Http\Middleware\Api\Application\AuthenticateApplicationUser;

class AuthenticateUserTest extends MiddlewareTestCase
{
    /**
     * Test that no user defined results in an access denied exception.
     */
    public function testNoUserDefined(): void
    {
        $this->expectException(AccessDeniedHttpException::class);

        $this->setRequestUserModel(null);

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Test that a non-admin user results in an exception.
     */
    public function testNonAdminUser(): void
    {
        $this->expectException(AccessDeniedHttpException::class);

        $this->generateRequestUserModel(false);

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Test that an admin user continues though the middleware.
     */
    public function testAdminUser(): void
    {
        $this->generateRequestUserModel(true);

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Return an instance of the middleware for testing.
     */
    private function getMiddleware(): AuthenticateApplicationUser
    {
        return new AuthenticateApplicationUser();
    }
}
