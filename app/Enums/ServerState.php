<?php

namespace App\Enums;

enum ServerState: string
{
    case Normal = 'normal';
    case Installing = 'installing';
    case InstallFailed = 'install_failed';
    case ReinstallFailed = 'reinstall_failed';
    case Suspended = 'suspended';
    case RestoringBackup = 'restoring_backup';

    public function icon(): string
    {
        return match ($this) {
            self::Normal => 'tabler-heart',
            self::Installing => 'tabler-heart-bolt',
            self::InstallFailed => 'tabler-heart-x',
            self::ReinstallFailed => 'tabler-heart-x',
            self::Suspended => 'tabler-heart-cancel',
            self::RestoringBackup => 'tabler-heart-up',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Normal => 'primary',
            self::Installing => 'primary',
            self::InstallFailed => 'danger',
            self::ReinstallFailed => 'danger',
            self::Suspended => 'warning',
            self::RestoringBackup => 'primary',
        };
    }
}
