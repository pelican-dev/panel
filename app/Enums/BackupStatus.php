<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum BackupStatus: string implements HasColor, HasIcon, HasLabel
{
    case InProgress = 'in_progress';
    case Successful = 'successful';
    case Failed = 'failed';

    public function getIcon(): BackedEnum
    {
        return match ($this) {
            self::InProgress => TablerIcon::CircleDashed,
            self::Successful => TablerIcon::CircleCheck,
            self::Failed => TablerIcon::CircleX,
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::InProgress => 'primary',
            self::Successful => 'success',
            self::Failed => 'danger',
        };
    }

    public function getLabel(): string
    {
        return trans('server/backup.backup_status.' . $this->value);
    }
}
