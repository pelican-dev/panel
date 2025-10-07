<?php

namespace App\Http\Middleware\Api\Daemon;

use App\Models\Node;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DaemonAuthenticate
{
    /**
     * Daemon routes that this middleware should be skipped on.
     *
     * @var string[]
     */
    protected array $except = [
        'daemon.configuration',
    ];

    /**
     * Check if a request from the daemon can be properly attributed back to a single node instance.
     *
     * @throws HttpException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (in_array($request->route()->getName(), $this->except)) {
            return $next($request);
        }

        if (is_null($bearer = $request->bearerToken())) {
            throw new HttpException(401, 'Access to this endpoint must include an Authorization header.', null, ['WWW-Authenticate' => 'Bearer']);
        }

        $parts = explode('.', $bearer);
        // Ensure that all the correct parts are provided in the header.
        if (count($parts) !== 2 || empty($parts[0]) || empty($parts[1])) {
            throw new BadRequestHttpException('The Authorization header provided was not in a valid format.');
        }

        /** @var Node $node */
        $node = Node::query()->where('daemon_token_id', $parts[0])->firstOrFail();

        if (hash_equals((string) $node->daemon_token, $parts[1])) {
            $request->attributes->set('node', $node);

            return $next($request);
        }

        throw new AccessDeniedHttpException('You are not authorized to access this resource.');
    }
}
