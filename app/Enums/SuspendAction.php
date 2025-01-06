<?php

namespace App\Enums;

enum SuspendAction: string
{
    case Suspend = 'suspend';
    case Unsuspend = 'unsuspend';
}
