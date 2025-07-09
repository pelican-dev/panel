<?php

namespace App\Extensions\Captcha\Schemas\Turnstile;

use App\Extensions\Captcha\CaptchaService;
use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\App;

class Rule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            App::call(fn (CaptchaService $service) => $service->get('turnstile')->validateResponse($value));
        } catch (Exception $exception) {
            report($exception);

            $fail('Captcha validation failed: ' . $exception->getMessage());
        }
    }
}
