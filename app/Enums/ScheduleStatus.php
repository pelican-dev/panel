<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ScheduleStatus: string implements HasColor, HasLabel
{
    case Inactive = 'inactive';
    case Processing = 'processing';
    case Active = 'active';

    public function getColor(): string
    {
        return match ($this) {
            self::Inactive => 'danger',
            self::Processing => 'warning',
            self::Active => 'success',
        };
    }

    public function getLabel(): string
    {
        return trans('server/schedule.schedule_status.' . $this->value);
    }
}
