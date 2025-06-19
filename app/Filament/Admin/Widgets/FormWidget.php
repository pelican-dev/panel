<?php

namespace App\Filament\Admin\Widgets;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Widgets\Widget;

abstract class FormWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static bool $isLazy = false;

    protected static string $view = 'filament.admin.widgets.form-widget';
}
