<?php

namespace App\Enums;

enum StartupVariableType: string
{
    case Text = 'text';
    case Number = 'number';
    case Select = 'select';
    case Toggle = 'toggle';
}
