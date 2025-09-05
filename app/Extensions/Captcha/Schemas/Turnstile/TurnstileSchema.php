<?php

namespace App\Extensions\Captcha\Schemas\Turnstile;

use App\Extensions\Captcha\Schemas\CaptchaSchemaInterface;
use App\Extensions\Captcha\Schemas\BaseSchema;
use Exception;
use Filament\Forms\Components\Component as BaseComponent;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;

class TurnstileSchema extends BaseSchema implements CaptchaSchemaInterface
{
    public function getId(): string
    {
        return 'turnstile';
    }

    public function isEnabled(): bool
    {
        return env('CAPTCHA_TURNSTILE_ENABLED', false);
    }

    public function getFormComponent(): BaseComponent
    {
        return Component::make('turnstile');
    }

    /**
     * @return array<string, string|string[]|bool|null>
     */
    public function getConfig(): array
    {
        return array_merge(parent::getConfig(), [
            'verify_domain' => env('CAPTCHA_TURNSTILE_VERIFY_DOMAIN'),
        ]);
    }

    /**
     * @return BaseComponent[]
     */
    public function getSettingsForm(): array
    {
        return array_merge(parent::getSettingsForm(), [
            Toggle::make('CAPTCHA_TURNSTILE_VERIFY_DOMAIN')
                ->label(trans('admin/setting.captcha.verify'))
                ->columnSpan(2)
                ->inline(false)
                ->onIcon('tabler-check')
                ->offIcon('tabler-x')
                ->onColor('success')
                ->offColor('danger')
                ->default(env('CAPTCHA_TURNSTILE_VERIFY_DOMAIN', true)),
            Placeholder::make('info')
                ->label(trans('admin/setting.captcha.info_label'))
                ->columnSpan(2)
                ->content(new HtmlString(trans('admin/setting.captcha.info'))),
        ]);
    }

    public function getIcon(): ?string
    {
        return 'tabler-brand-cloudflare';
    }

    /**
     * @throws Exception
     */
    public function validateResponse(?string $captchaResponse = null): void
    {
        $captchaResponse ??= request()->get('cf-turnstile-response');

        if (!$secret = env('CAPTCHA_TURNSTILE_SECRET_KEY')) {
            throw new Exception('Turnstile secret key is not defined.');
        }

        $response = Http::asJson()
            ->timeout(15)
            ->connectTimeout(5)
            ->throw()
            ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secret,
                'response' => $captchaResponse,
            ])
            ->json();

        if (!$response['success']) {
            match ($response['error-codes'][0] ?? null) {
                'missing-input-secret' => throw new Exception('The secret parameter was not passed.'),
                'invalid-input-secret' => throw new Exception('The secret parameter was invalid, did not exist, or is a testing secret key with a non-testing response.'),
                'missing-input-response' => throw new Exception('The response parameter (token) was not passed.'),
                'invalid-input-response' => throw new Exception('The response parameter (token) is invalid or has expired.'),
                'bad-request' => throw new Exception('The request was rejected because it was malformed.'),
                'timeout-or-duplicate' => throw new Exception('The response parameter (token) has already been validated before.'),
                default => throw new Exception('An internal error happened while validating the response.'),
            };
        }

        if (!$this->verifyDomain($response['hostname'] ?? '')) {
            throw new Exception('Domain verification failed.');
        }
    }

    private function verifyDomain(string $hostname): bool
    {
        if (!env('CAPTCHA_TURNSTILE_VERIFY_DOMAIN', true)) {
            return true;
        }

        $requestUrl = parse_url(request()->url());

        return $hostname === array_get($requestUrl, 'host');
    }
}
