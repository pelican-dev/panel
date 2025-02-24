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
    public function test_no_user_defined(): void
    {
        $this->expectException(AccessDeniedHttpException::class);

        $this->setRequestUserModel(null);

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Test that a non-admin user results in an exception.
     */
    public function test_non_admin_user(): void
    {
        $this->expectException(AccessDeniedHttpException::class);

        $this->generateRequestUserModel(false);

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Test that an admin user continues though the middleware.
     */
    public function test_admin_user(): void
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
