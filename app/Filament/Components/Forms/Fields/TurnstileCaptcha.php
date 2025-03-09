<?php

namespace App\Filament\Components\Forms\Fields;

use App\Rules\ValidTurnstileCaptcha;
use Filament\Forms\Components\Field;

class TurnstileCaptcha extends Field
{
    protected string $viewIdentifier = 'turnstile';

    protected string $view = 'filament.components.turnstile-captcha';

    protected function setUp(): void
    {
        parent::setUp();

        $this->hiddenLabel();

        $this->required();

        $this->after(function (TurnstileCaptcha $component) {
            $component->rule(new ValidTurnstileCaptcha());
        });
    }
}
