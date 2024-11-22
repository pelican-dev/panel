<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Events\Auth\FailedCaptcha;
use Coderflex\LaravelTurnstile\Facades\LaravelTurnstile;
use Symfony\Component\HttpKernel\Exception\HttpException;

readonly class VerifyReCaptcha
{
    public function __construct(private Application $app) {}

    public function handle(Request $request, \Closure $next): mixed
    {
        if (!config('turnstile.turnstile_enabled')) {
            return $next($request);
        }

        if ($this->app->isLocal()) {
            return $next($request);
        }

        if ($request->filled('cf-turnstile-response')) {
            $response = LaravelTurnstile::validate($request->get('cf-turnstile-response'));

            if ($response['success'] && $this->isResponseVerified($response['hostname'] ?? '', $request)) {
                return $next($request);
            }
        }

        event(new FailedCaptcha($request->ip(), $response['message'] ?? null));

        throw new HttpException(Response::HTTP_BAD_REQUEST, 'Failed to validate turnstile captcha data.');
    }

    /**
     * Determine if the response from the recaptcha servers was valid.
     */
    private function isResponseVerified(string $hostname, Request $request): bool
    {
        if (!config('turnstile.turnstile_verify_domain')) {
            return true;
        }

        $url = parse_url($request->url());

        return $hostname === array_get($url, 'host');
    }
}
