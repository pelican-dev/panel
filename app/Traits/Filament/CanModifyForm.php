<?php

namespace App\Traits\Filament;

use Closure;
use Filament\Forms\Form;

trait CanModifyForm
{
    /** @var array<Closure> */
    protected static array $customFormModifications = [];

    public static function modifyForm(Closure $closure): void
    {
        static::$customFormModifications[] = $closure;
    }

    public static function defaultForm(Form $form): Form
    {
        return $form;
    }

    public static function form(Form $form): Form
    {
        $form = static::defaultForm($form);

        foreach (static::$customFormModifications as $closure) {
            $form = $closure($form);
        }

        return $form;
    }
}
