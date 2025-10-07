<?php

namespace App\Tests\Unit\Http\Middleware;

use App\Tests\Assertions\MiddlewareAttributeAssertionsTrait;
use App\Tests\TestCase;
use App\Tests\Traits\Http\MocksMiddlewareClosure;
use App\Tests\Traits\Http\RequestMockHelpers;

abstract class MiddlewareTestCase extends TestCase
{
    use MiddlewareAttributeAssertionsTrait;
    use MocksMiddlewareClosure;
    use RequestMockHelpers;

    /**
     * Setup tests with a mocked request object and normal attributes.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->buildRequestMock();
    }
}
