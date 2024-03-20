<?php

namespace App\Http\Middleware\Api\Client;

use App\Models\Server;
use Closure;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Routing\Middleware\SubstituteBindings;

class SubstituteClientBindings extends SubstituteBindings
{
    public function __construct(Registrar $router, private Server $server)
    {
        parent::__construct($router);
    }

    public function handle($request, Closure $next): mixed
    {
        // Override default behavior of the model binding to use a specific table column rather than the default 'id'.
        $this->router->bind('server', function ($value) {
            return $this->server->query()->where(strlen($value) === 8 ? 'uuidShort' : 'uuid', $value)->firstOrFail();
        });

        $this->router->bind('user', function ($value, $route) {
            /** @var \App\Models\Subuser $match */
            $match = $route->parameter('server')
                ->subusers()
                ->whereRelation('user', 'uuid', '=', $value)
                ->firstOrFail();

            return $match->user;
        });

        return parent::handle($request, $next);
    }
}
