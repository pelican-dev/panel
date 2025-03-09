<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Events\Auth\FailedCaptcha;
use App\Extensions\Captcha\Providers\CaptchaProvider;
use Symfony\Component\HttpKernel\Exception\HttpException;

readonly class VerifyCaptcha
{
    public function __construct(private Application $app) {}

    public function handle(Request $request, Closure $next): mixed
    {
        if ($this->app->isLocal()) {
            return $next($request);
        }

        $captchaProviders = collect(CaptchaProvider::get())->filter(fn (CaptchaProvider $provider) => $provider->isEnabled())->all();
        foreach ($captchaProviders as $captchaProvider) {
            $response = $captchaProvider->validateResponse();

            if ($response['success'] && $captchaProvider->verifyDomain($response['hostname'] ?? '', $request->url())) {
                return $next($request);
            }

            event(new FailedCaptcha($request->ip(), $response['message'] ?? null));

            throw new HttpException(Response::HTTP_BAD_REQUEST, "Failed to validate {$captchaProvider->getId()} captcha data.");
        }

        // No captcha enabled
        return $next($request);
    }
}
