<?php

namespace App\Enums;

enum ConsoleWidgetPosition: string
{
    case Top = 'top';
    case AboveConsole = 'above_console';
    case BelowConsole = 'below_console';
    case Bottom = 'bottom';
}
