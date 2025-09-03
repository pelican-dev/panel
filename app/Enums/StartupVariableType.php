<?php

namespace App\Enums;

enum StartupVariableType: string
{
    case Text = 'text';
    case Select = 'select';
    case Toggle = 'toggle'; // TODO: add toggle to blade view
}
