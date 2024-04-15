<?php

namespace App\Enums;

enum ContainerStatus: string
{
    case Created = 'created';
    case Running = 'running';
    case Restarting = 'restarting';
    case Exited = 'exited';
    case Paused = 'paused';
    case Dead = 'dead';
    case Removing = 'removing';
}
