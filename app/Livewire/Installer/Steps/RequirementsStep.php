<?php

namespace App\Livewire\Installer\Steps;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard\Step;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;

class RequirementsStep
{
    public const MIN_PHP_VERSION = '8.2';

    public static function make(): Step
    {
        $compare = version_compare(phpversion(), self::MIN_PHP_VERSION);
        $correctPhpVersion = $compare >= 0;

        $fields = [
            Section::make(trans('installer.requirements.sections.version.title'))
                ->description(trans('installer.requirements.sections.version.or_newer', ['version' => self::MIN_PHP_VERSION]))
                ->icon($correctPhpVersion ? 'tabler-check' : 'tabler-x')
                ->iconColor($correctPhpVersion ? 'success' : 'danger')
                ->schema([
                    Placeholder::make('')
                        ->content(trans('installer.requirements.sections.version.content', ['version' => PHP_VERSION])),
                ]),
        ];

        $phpExtensions = [
            'BCMath' => extension_loaded('bcmath'),
            'cURL' => extension_loaded('curl'),
            'GD' => extension_loaded('gd'),
            'intl' => extension_loaded('intl'),
            'mbstring' => extension_loaded('mbstring'),
            'MySQL' => extension_loaded('pdo_mysql'),
            'SQLite3' => extension_loaded('pdo_sqlite'),
            'XML' => extension_loaded('xml'),
            'Zip' => extension_loaded('zip'),
        ];
        $allExtensionsInstalled = !in_array(false, $phpExtensions);

        $fields[] = Section::make(trans('installer.requirements.sections.extensions.title'))
            ->description(implode(', ', array_keys($phpExtensions)))
            ->icon($allExtensionsInstalled ? 'tabler-check' : 'tabler-x')
            ->iconColor($allExtensionsInstalled ? 'success' : 'danger')
            ->schema([
                Placeholder::make('')
                    ->content(trans('installer.requirements.sections.extensions.good'))
                    ->visible($allExtensionsInstalled),
                Placeholder::make('')
                    ->content(trans('installer.requirements.sections.extensions.bad', ['extensions' => implode(', ', array_keys($phpExtensions, false))]))
                    ->visible(!$allExtensionsInstalled),
            ]);

        $folderPermissions = [
            'Storage' => substr(sprintf('%o', fileperms(base_path('storage/'))), -4) >= 755,
            'Cache' => substr(sprintf('%o', fileperms(base_path('bootstrap/cache/'))), -4) >= 755,
        ];
        $correctFolderPermissions = !in_array(false, $folderPermissions);

        $fields[] = Section::make(trans('installer.requirements.sections.permissions.title'))
            ->description(implode(', ', array_keys($folderPermissions)))
            ->icon($correctFolderPermissions ? 'tabler-check' : 'tabler-x')
            ->iconColor($correctFolderPermissions ? 'success' : 'danger')
            ->schema([
                Placeholder::make('')
                    ->content(trans('installer.requirements.sections.permissions.good'))
                    ->visible($correctFolderPermissions),
                Placeholder::make('')
                    ->content(trans('installer.requirements.sections.permissions.bad', ['folders' => implode(', ', array_keys($folderPermissions, false))]))
                    ->visible(!$correctFolderPermissions),
            ]);

        return Step::make('requirements')
            ->label(trans('installer.requirements.title'))
            ->schema($fields)
            ->afterValidation(function () use ($correctPhpVersion, $allExtensionsInstalled, $correctFolderPermissions) {
                if (!$correctPhpVersion || !$allExtensionsInstalled || !$correctFolderPermissions) {
                    Notification::make()
                        ->title(trans('installer.requirements.exception'))
                        ->danger()
                        ->send();

                    throw new Halt(trans('installer.requirements.title'));
                }
            });
    }
}
