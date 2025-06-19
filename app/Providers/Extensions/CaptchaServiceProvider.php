<?php

namespace App\Providers\Extensions;

use App\Extensions\Captcha\CaptchaService;
use App\Extensions\Captcha\Schemas\Turnstile\TurnstileSchema;
use Illuminate\Support\ServiceProvider;

class CaptchaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CaptchaService::class, function ($app) {
            $service = new CaptchaService();

            // Default Captcha providers
            $service->register(new TurnstileSchema());

            return $service;
        });
    }
}
