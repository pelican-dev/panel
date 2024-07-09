<?php

namespace App\Filament\Pages;

use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use ShuvroRoy\FilamentSpatieLaravelHealth\Pages\HealthCheckResults as BaseHealthCheckResults;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\Health\ResultStores\ResultStore;

class HealthCheckResults extends BaseHealthCheckResults
{
    protected static ?string $slug = 'health';

    public static function getNavigationGroup(): ?string
    {
        return 'Advanced';
    }

    protected function getViewData(): array
    {
        $checkResults = app(ResultStore::class)->latestResults();

        if ($checkResults === null) {
            Artisan::call(RunHealthChecksCommand::class);

            $this->dispatch('refresh-component');
        }

        return [
            'lastRanAt' => new Carbon($checkResults?->finishedAt),
            'checkResults' => $checkResults,
        ];
    }
}
