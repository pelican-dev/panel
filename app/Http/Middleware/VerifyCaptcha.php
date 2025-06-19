<?php

namespace App\Http\Middleware;

use App\Extensions\Captcha\CaptchaService;
use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Events\Auth\FailedCaptcha;
use Symfony\Component\HttpKernel\Exception\HttpException;

readonly class VerifyCaptcha
{
    public function __construct(private Application $app) {}

    public function handle(Request $request, Closure $next, CaptchaService $captchaService): mixed
    {
        if ($this->app->isLocal()) {
            return $next($request);
        }

        $schemas = $captchaService->getActiveSchemas();
        foreach ($schemas as $schema) {
            $response = $schema->validateResponse();

            if ($response['success'] && $schema->verifyDomain($response['hostname'] ?? '', $request->url())) {
                return $next($request);
            }

            event(new FailedCaptcha($request->ip(), $response['message'] ?? null));

            throw new HttpException(Response::HTTP_BAD_REQUEST, "Failed to validate {$schema->getId()} captcha data.");
        }

        // No captcha enabled
        return $next($request);
    }
}
