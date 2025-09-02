<?php

namespace App\Enums;

enum StartupVariableType: string
{
    case Text = 'text';
    case Select = 'select';
    case Toggle = 'toggle';
}
