<?php

namespace App\Enums;

enum ServerState: string
{
    case None = 'none';
    case Installing = 'installing';
    case InstallFailed = 'install_failed';
    case ReinstallFailed = 'reinstall_failed';
    case Suspended = 'suspended';
    case RestoringBackup = 'restoring_backup';

    public function icon(): string
    {
        return match ($this) {
            self::None => 'tabler-heart',
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
            self::None => 'primary',
            self::Installing => 'info',
            self::InstallFailed => 'danger',
            self::ReinstallFailed => 'danger',
            self::Suspended => 'danger',
            self::RestoringBackup => 'info',
        };
    }
}
