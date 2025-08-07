<?php

namespace App\Extensions\Captcha\Schemas\Turnstile;

use Filament\Forms\Components\Field;

class Component extends Field
{
    protected string $viewIdentifier = 'turnstile';

    protected string $view = 'filament.components.turnstile-captcha';

    protected function setUp(): void
    {
        parent::setUp();

        $this->hiddenLabel();

        $this->required();

        // Remove automatic validation rule to prevent validation during state sync
        // CAPTCHA validation will be handled manually in the authenticate method
        // $this->rule(new Rule());
    }
}
