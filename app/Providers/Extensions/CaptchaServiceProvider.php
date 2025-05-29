<?php

namespace App\Providers\Extensions;

use App\Extensions\Captcha\CaptchaProvider;
use App\Extensions\Captcha\Schemas\Turnstile\TurnstileSchema;
use Illuminate\Support\ServiceProvider;

class CaptchaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CaptchaProvider::class, function ($app) {
            $service = new CaptchaProvider();

            // Default Captcha providers
            $service->register(new TurnstileSchema());

            return $service;
        });
    }
}
