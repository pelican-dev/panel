<?php

namespace App\Extensions\Captcha\Schemas\Turnstile;

use App\Extensions\Captcha\CaptchaService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\App;

class Rule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = App::call(fn (CaptchaService $service) => $service->getActiveSchema()->validateResponse($value));

        if (!$response['success']) {
            $fail($response['message'] ?? 'Unknown error occurred, please try again');
        }
    }
}
