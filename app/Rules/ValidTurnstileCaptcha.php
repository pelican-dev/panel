<?php

namespace App\Rules;

use App\Extensions\Captcha\Providers\CaptchaProvider;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidTurnstileCaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = CaptchaProvider::get('turnstile')->validateResponse($value);

        if (!$response['success']) {
            $fail($response['message'] ?? 'Unknown error occurred, please try again');
        }
    }
}
