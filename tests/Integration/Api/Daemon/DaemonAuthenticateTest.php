<?php

namespace App\Tests\Integration\Api\Daemon;

use App\Http\Middleware\Api\Daemon\DaemonAuthenticate;
use App\Models\Node;
use App\Tests\Unit\Http\Middleware\MiddlewareTestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DaemonAuthenticateTest extends MiddlewareTestCase
{
    /**
     * Test that if we are accessing the daemon configuration route this middleware is not
     * applied in order to allow an unauthenticated request to use a token to grab data.
     */
    public function test_response_should_continue_if_route_is_exempted(): void
    {
        $this->request->expects('route->getName')->withNoArgs()->andReturn('daemon.configuration');

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Test that not passing in the bearer token will result in a HTTP/401 error with the
     * proper response headers.
     */
    public function test_response_should_fail_if_no_token_is_provided(): void
    {
        $this->request->expects('route->getName')->withNoArgs()->andReturn('random.route');
        $this->request->expects('bearerToken')->withNoArgs()->andReturnNull();

        try {
            $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
        } catch (HttpException $exception) {
            $this->assertEquals(401, $exception->getStatusCode(), 'Assert that a status code of 401 is returned.');
            $this->assertTrue(is_array($exception->getHeaders()), 'Assert that an array of headers is returned.');
            $this->assertArrayHasKey('WWW-Authenticate', $exception->getHeaders(), 'Assert exception headers contains WWW-Authenticate.');
            $this->assertEquals('Bearer', $exception->getHeaders()['WWW-Authenticate']);
        }
    }

    /**
     * Test that passing in an invalid node daemon secret will result in a bad request
     * exception being returned.
     */
    #[DataProvider('badTokenDataProvider')]
    public function test_response_should_fail_if_token_format_is_incorrect(string $token): void
    {
        $this->expectException(BadRequestHttpException::class);

        $this->request->expects('route->getName')->withNoArgs()->andReturn('random.route');
        $this->request->expects('bearerToken')->withNoArgs()->andReturn($token);

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Test that an access denied error is returned if the node is valid but the token
     * provided is not valid.
     */
    public function test_response_should_fail_if_token_is_not_valid(): void
    {
        $node = Node::factory()->create();

        $this->expectException(AccessDeniedHttpException::class);

        $this->request->expects('route->getName')->withNoArgs()->andReturn('random.route');
        $this->request->expects('bearerToken')->withNoArgs()->andReturn($node->daemon_token_id . '.random_string_123');

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Test that an access denied exception is returned if the node is not found using
     * the token ID provided.
     */
    public function test_response_should_fail_if_node_is_not_found(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->request->expects('route->getName')->withNoArgs()->andReturn('random.route');
        $this->request->expects('bearerToken')->withNoArgs()->andReturn('abcd1234.random_string_123');

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Test a successful middleware process.
     */
    public function test_successful_middleware_process(): void
    {
        $node = Node::factory()->create();
        $node->daemon_token = 'the_same';
        $node->save();

        $this->request->expects('route->getName')->withNoArgs()->andReturn('random.route');
        $this->request->expects('bearerToken')->withNoArgs()->andReturn($node->daemon_token_id . '.the_same');

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
        $this->assertRequestHasAttribute('node');
        $this->assertRequestAttributeEquals($node->fresh(), 'node');
    }

    /**
     * Provides different tokens that should trigger a bad request exception due to
     * their formatting.
     *
     * @return array|\string[][]
     */
    public static function badTokenDataProvider(): array
    {
        return [
            ['foo'],
            ['foobar'],
            ['foo-bar'],
            ['foo.bar.baz'],
            ['.foo'],
            ['foo.'],
            ['foo..bar'],
        ];
    }

    /**
     * Return an instance of the middleware using mocked dependencies.
     */
    private function getMiddleware(): DaemonAuthenticate
    {
        return new DaemonAuthenticate();
    }
}
