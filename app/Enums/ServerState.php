<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ServerState: string implements HasColor, HasIcon, HasLabel
{
    case Installing = 'installing';
    case InstallFailed = 'install_failed';
    case ReinstallFailed = 'reinstall_failed';
    case Suspended = 'suspended';
    case RestoringBackup = 'restoring_backup';

    public function getIcon(): string
    {
        return match ($this) {
            self::Installing => 'tabler-heart-bolt',
            self::InstallFailed => 'tabler-heart-x',
            self::ReinstallFailed => 'tabler-heart-x',
            self::Suspended => 'tabler-heart-cancel',
            self::RestoringBackup => 'tabler-heart-up',
        };
    }

    public function getColor(bool $hex = false): string
    {
        if ($hex) {
            return match ($this) {
                self::Installing, self::RestoringBackup => '#2563EB',
                self::Suspended => '#D97706',
                self::InstallFailed, self::ReinstallFailed => '#EF4444',
            };
        }

        return match ($this) {
            self::Installing => 'primary',
            self::InstallFailed => 'danger',
            self::ReinstallFailed => 'danger',
            self::Suspended => 'warning',
            self::RestoringBackup => 'primary',
        };
    }

    public function getLabel(): string
    {
        return str($this->value)->headline();
    }
}
