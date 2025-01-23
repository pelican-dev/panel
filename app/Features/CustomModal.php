<?php

namespace App\Features;

use Filament\Forms\Components\Concerns\HasActions;
use Filament\Forms\Components\Field;
use Filament\Support\Concerns\HasDescription;
use Filament\Support\Concerns\HasHeading;

class CustomModal extends Field
{
    use HasActions;
    use HasDescription;
    use HasHeading;

    protected string $view = 'livewire.custom-modal';
}
