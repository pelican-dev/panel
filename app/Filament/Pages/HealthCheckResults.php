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
    protected static ?int $navigationSort = 4;

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

    public static function getNavigationGroup(): ?string
    {
        return 'Advanced';
    }

    public static function getNavigationLabel(): string
    {
        return 'Panel Health';
    }

    public static function getNavigationBadge(): ?string
    {
        $results = app(ResultStore::class)->latestResults();

        if ($results === null) {
            return null;
        }

        $results = json_decode($results->toJson(), true);

        $failed = array_reduce($results['checkResults'], function ($numFailed, $result) {
            return $numFailed + ($result['status'] === 'failed' ? 1 : 0);
        }, 0);

        return $failed === 0 ? null : (string) $failed;
    }

    public static function getNavigationBadgeColor(): string
    {
        return self::getNavigationBadge() > null ? 'danger' : '';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        $results = app(ResultStore::class)->latestResults();

        if ($results === null) {
            return null;
        }

        $results = json_decode($results->toJson(), true);

        $failedNames = array_reduce($results['checkResults'], function ($carry, $result) {
            if ($result['status'] === 'failed') {
                $carry[] = $result['name'];
            }

            return $carry;
        }, []);

        return 'Failed: ' . implode(', ', $failedNames);
    }

    public static function getNavigationIcon(): string
    {
        $results = app(ResultStore::class)->latestResults();

        if ($results === null) {
            return 'tabler-heart-question';
        }

        return $results->containsFailingCheck() ? 'tabler-heart-exclamation' : 'tabler-heart-check';
    }

    public static function getSlug(): string
    {
        return 'health';
    }
}
