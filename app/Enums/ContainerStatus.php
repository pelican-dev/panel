<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ContainerStatus: string implements HasColor, HasIcon, HasLabel
{
    // Docker Based
    case Created = 'created';
    case Starting = 'starting';
    case Running = 'running';
    case Restarting = 'restarting';
    case Exited = 'exited';
    case Paused = 'paused';
    case Dead = 'dead';
    case Removing = 'removing';
    case Stopping = 'stopping';
    case Offline = 'offline';

    // HTTP Based
    case Missing = 'missing';

    public function getIcon(): string
    {
        return match ($this) {

            self::Created => 'tabler-heart-plus',
            self::Starting => 'tabler-heart-up',
            self::Running => 'tabler-heartbeat',
            self::Restarting => 'tabler-heart-bolt',
            self::Exited => 'tabler-heart-exclamation',
            self::Paused => 'tabler-heart-pause',
            self::Dead, self::Offline => 'tabler-heart-x',
            self::Removing => 'tabler-heart-down',
            self::Missing => 'tabler-heart-search',
            self::Stopping => 'tabler-heart-minus',
        };
    }

    public function getColor(bool $hex = false): string
    {
        if ($hex) {
            return match ($this) {
                self::Created, self::Restarting => '#2563EB',
                self::Starting, self::Paused, self::Removing, self::Stopping => '#D97706',
                self::Running => '#22C55E',
                self::Exited, self::Missing, self::Dead, self::Offline => '#EF4444',
            };
        }

        return match ($this) {
            self::Created => 'primary',
            self::Starting => 'warning',
            self::Running => 'success',
            self::Restarting => 'info',
            self::Exited => 'danger',
            self::Paused => 'warning',
            self::Dead => 'danger',
            self::Removing => 'warning',
            self::Missing => 'danger',
            self::Stopping => 'warning',
            self::Offline => 'gray',
        };
    }

    public function getLabel(): string
    {
        return str($this->value)->title();
    }

    public function isOffline(): bool
    {
        return in_array($this, [ContainerStatus::Offline, ContainerStatus::Missing]);
    }

    public function isStartingOrRunning(): bool
    {
        return in_array($this, [ContainerStatus::Starting, ContainerStatus::Running]);
    }

    public function isStartingOrStopping(): bool
    {
        return in_array($this, [ContainerStatus::Starting, ContainerStatus::Stopping, ContainerStatus::Restarting]);
    }

    public function isStartable(): bool
    {
        return !in_array($this, [ContainerStatus::Running, ContainerStatus::Starting, ContainerStatus::Stopping, ContainerStatus::Restarting]);
    }

    public function isRestartable(): bool
    {
        if ($this->isStartable()) {
            return true;
        }

        return !in_array($this, [ContainerStatus::Offline]);
    }

    public function isStoppable(): bool
    {
        return !in_array($this, [ContainerStatus::Starting, ContainerStatus::Stopping, ContainerStatus::Restarting, ContainerStatus::Exited, ContainerStatus::Offline]);
    }

    public function isKillable(): bool
    {
        // [ContainerStatus::Restarting, ContainerStatus::Removing, ContainerStatus::Dead, ContainerStatus::Created]

        return !in_array($this, [ContainerStatus::Offline, ContainerStatus::Running, ContainerStatus::Exited]);
    }
}
