<?php

namespace App\Filament\Admin\Pages;

use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\Health\ResultStores\ResultStore;

class Health extends Page
{
    protected static ?string $navigationIcon = 'tabler-heart';

    protected static ?string $navigationGroup = 'Advanced';

    protected static string $view = 'filament.pages.health';

    // @phpstan-ignore-next-line
    protected $listeners = [
        'refresh-component' => '$refresh',
    ];

    protected function getActions(): array
    {
        return [
            Action::make('refresh')
                ->button()
                ->action('refresh'),
        ];
    }

    protected function getViewData(): array
    {
        // @phpstan-ignore-next-line
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

    public function refresh(): void
    {
        Artisan::call(RunHealthChecksCommand::class);

        $this->dispatch('refresh-component');

        Notification::make()
            ->title('Health check results refreshed')
            ->success()
            ->send();
    }

    public static function getNavigationBadge(): ?string
    {
        // @phpstan-ignore-next-line
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
        // @phpstan-ignore-next-line
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
        // @phpstan-ignore-next-line
        $results = app(ResultStore::class)->latestResults();

        if ($results === null) {
            return 'tabler-heart-question';
        }

        return $results->containsFailingCheck() ? 'tabler-heart-exclamation' : 'tabler-heart-check';
    }
}
