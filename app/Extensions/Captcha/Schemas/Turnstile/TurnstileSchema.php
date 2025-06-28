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
     * @return array<string, string|bool>
     */
    public function validateResponse(?string $captchaResponse = null): array
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
            ]);

        return count($response->json()) ? $response->json() : [
            'success' => false,
            'message' => 'Unknown error occurred, please try again',
        ];
    }

    public function verifyDomain(string $hostname, ?string $requestUrl = null): bool
    {
        if (!env('CAPTCHA_TURNSTILE_VERIFY_DOMAIN', true)) {
            return true;
        }

        $requestUrl ??= request()->url;
        $requestUrl = parse_url($requestUrl);

        return $hostname === array_get($requestUrl, 'host');
    }
}
