<?php

namespace App\Filament\Components\Forms\Actions;

use Filament\Actions\Action;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class CronPresetAction extends Action
{
    protected string $minute = '0';

    protected string $hour = '0';

    protected string $dayOfMonth = '*';

    protected string $month = '*';

    protected string $dayOfWeek = '*';

    protected function setUp(): void
    {
        parent::setUp();

        $this->disabled(fn (string $operation) => $operation === 'view');

        $this->color(fn (Get $get) => $get('cron_minute') == $this->minute &&
                                    $get('cron_hour') == $this->hour &&
                                    $get('cron_day_of_month') == $this->dayOfMonth &&
                                    $get('cron_month') == $this->month &&
                                    $get('cron_day_of_week') == $this->dayOfWeek
            ? 'success' : 'primary');

        $this->action(function (Set $set) {
            $set('cron_minute', $this->minute);
            $set('cron_hour', $this->hour);
            $set('cron_day_of_month', $this->dayOfMonth);
            $set('cron_month', $this->month);
            $set('cron_day_of_week', $this->dayOfWeek);
        });
    }

    public function cron(string $minute, string $hour, string $dayOfMonth, string $month, string $dayOfWeek): static
    {
        $this->minute = $minute;
        $this->hour = $hour;
        $this->dayOfMonth = $dayOfMonth;
        $this->month = $month;
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }
}
