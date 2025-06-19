<?php

namespace App\Extensions\Captcha\Schemas;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

abstract class BaseSchema
{
    abstract public function getId(): string;

    public function getName(): string
    {
        return Str::upper($this->getId());
    }

    /**
     * @return array<string, string|string[]|bool|null>
     */
    public function getConfig(): array
    {
        $id = Str::upper($this->getId());

        return [
            'site_key' => env("CAPTCHA_{$id}_SITE_KEY"),
            'secret_key' => env("CAPTCHA_{$id}_SECRET_KEY"),
        ];
    }

    /**
     * @return Component[]
     */
    public function getSettingsForm(): array
    {
        $id = Str::upper($this->getId());

        return [
            TextInput::make("CAPTCHA_{$id}_SITE_KEY")
                ->label('Site Key')
                ->placeholder('Site Key')
                ->columnSpan(2)
                ->required()
                ->password()
                ->revealable()
                ->autocomplete(false)
                ->default(env("CAPTCHA_{$id}_SITE_KEY")),
            TextInput::make("CAPTCHA_{$id}_SECRET_KEY")
                ->label('Secret Key')
                ->placeholder('Secret Key')
                ->columnSpan(2)
                ->required()
                ->password()
                ->revealable()
                ->autocomplete(false)
                ->default(env("CAPTCHA_{$id}_SECRET_KEY")),
        ];
    }

    public function verifyDomain(string $hostname, ?string $requestUrl = null): bool
    {
        return true;
    }
}
