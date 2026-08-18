<?php

namespace App\Livewire\Installer\Steps;

use App\Enums\TablerIcon;
use App\Services\Environment\InstallationHealthService;
use App\ValueObjects\EnvironmentCheckResult;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Exceptions\Halt;

class RequirementsStep
{
    public const MIN_PHP_VERSION = InstallationHealthService::MIN_PHP_VERSION;

    public static function make(InstallationHealthService $health): Step
    {
        $checks = $health->systemRequirements();

        $fields = array_map(
            fn (EnvironmentCheckResult $check) => Section::make($check->label)
                ->description($check->remediation)
                ->icon($check->failed() ? TablerIcon::X : TablerIcon::Check)
                ->iconColor($check->failed() ? 'danger' : 'success')
                ->schema([
                    TextEntry::make($check->key)
                        ->hiddenLabel()
                        ->state($check->message),
                ]),
            $checks,
        );

        return Step::make('requirements')
            ->label(trans('installer.requirements.title'))
            ->schema($fields)
            ->afterValidation(function () use ($checks, $health) {
                if ($health->hasFailures($checks)) {
                    Notification::make()
                        ->title(trans('installer.health.preflight_failed'))
                        ->danger()
                        ->send();

                    throw new Halt(trans('installer.requirements.title'));
                }
            });
    }
}
