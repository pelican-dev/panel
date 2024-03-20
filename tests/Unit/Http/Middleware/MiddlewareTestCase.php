<?php

namespace App\Tests\Unit\Http\Middleware;

use App\Tests\TestCase;
use App\Tests\Traits\Http\RequestMockHelpers;
use App\Tests\Traits\Http\MocksMiddlewareClosure;
use App\Tests\Assertions\MiddlewareAttributeAssertionsTrait;

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
