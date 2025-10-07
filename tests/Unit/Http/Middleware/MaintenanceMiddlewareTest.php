<?php

namespace App\Tests\Unit\Http\Middleware;

use App\Http\Middleware\MaintenanceMiddleware;
use App\Models\Node;
use App\Models\Server;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Mockery as m;
use Mockery\MockInterface;

class MaintenanceMiddlewareTest extends MiddlewareTestCase
{
    private MockInterface $response;

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->response = m::mock(ResponseFactory::class);
    }

    /**
     * Test that a node not in maintenance mode continues through the request cycle.
     */
    public function test_handle(): void
    {
        // maintenance mode is off by default
        $server = new Server();

        $node = new Node([
            'maintenance_mode' => false,
        ]);
        $server->setRelation('node', $node);

        $this->setRequestAttribute('server', $server);

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Test that a node in maintenance mode returns an error view.
     */
    public function test_handle_in_maintenance_mode(): void
    {
        $server = new Server();

        $node = new Node([
            'maintenance_mode' => true,
        ]);
        $server->setRelation('node', $node);

        $this->setRequestAttribute('server', $server);

        $this->response->shouldReceive('view')
            ->once()
            ->with('errors.maintenance')
            ->andReturn(new Response());

        $response = $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
        $this->assertInstanceOf(Response::class, $response);
    }

    private function getMiddleware(): MaintenanceMiddleware
    {
        return new MaintenanceMiddleware($this->response);
    }
}
