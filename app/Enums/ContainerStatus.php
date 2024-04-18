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
    case Missing = 'missing';

    public function icon(): string
    {
        return match ($this) {
            self::Created => 'tabler-heart-plus',
            self::Running => 'tabler-heartbeat',
            self::Restarting => 'tabler-heart-bolt',
            self::Exited => 'tabler-heart-exclamation',
            self::Paused => 'tabler-heart-pause',
            self::Dead => 'tabler-heart-x',
            self::Removing => 'tabler-heart-down',
            self::Missing => 'tabler-heart-question',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Created => 'primary',
            self::Running => 'success',
            self::Restarting => 'info',
            self::Exited => 'danger',
            self::Paused => 'warning',
            self::Dead => 'danger',
            self::Removing => 'warning',
            self::Missing => 'gray',
        };
    }
}
