<?php

namespace App\Livewire\Installer\Steps;

use Filament\Schemas\Components\Wizard\Step;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Support\Exceptions\Halt;

class RequirementsStep
{
    public const MIN_PHP_VERSION = '8.2';

    public static function make(): Step
    {
        $compare = version_compare(phpversion(), self::MIN_PHP_VERSION);
        $correctPhpVersion = $compare >= 0;

        $fields = [
            Section::make('PHP Version')
                ->description(self::MIN_PHP_VERSION . ' or newer')
                ->icon($correctPhpVersion ? 'tabler-check' : 'tabler-x')
                ->iconColor($correctPhpVersion ? 'success' : 'danger')
                ->schema([
                    TextEntry::make('php_version')
                        ->hiddenLabel()
                        ->state('Your PHP Version is ' . PHP_VERSION . '.'),
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

        $fields[] = Section::make('PHP Extensions')
            ->description(implode(', ', array_keys($phpExtensions)))
            ->icon($allExtensionsInstalled ? 'tabler-check' : 'tabler-x')
            ->iconColor($allExtensionsInstalled ? 'success' : 'danger')
            ->schema([
                TextEntry::make('all_extensions_installed')
                    ->hiddenLabel()
                    ->state('All needed PHP Extensions are installed.')
                    ->visible($allExtensionsInstalled),
                TextEntry::make('extensions_missing')
                    ->hiddenLabel()
                    ->state('The following PHP Extensions are missing: ' . implode(', ', array_keys($phpExtensions, false)))
                    ->visible(!$allExtensionsInstalled),
            ]);

        $folderPermissions = [
            'Storage' => substr(sprintf('%o', fileperms(base_path('storage/'))), -4) >= 755,
            'Cache' => substr(sprintf('%o', fileperms(base_path('bootstrap/cache/'))), -4) >= 755,
        ];
        $correctFolderPermissions = !in_array(false, $folderPermissions);

        $fields[] = Section::make('Folder Permissions')
            ->description(implode(', ', array_keys($folderPermissions)))
            ->icon($correctFolderPermissions ? 'tabler-check' : 'tabler-x')
            ->iconColor($correctFolderPermissions ? 'success' : 'danger')
            ->schema([
                TextEntry::make('correct_folder_permissions')
                    ->hiddenLabel()
                    ->state('All Folders have the correct permissions.')
                    ->visible($correctFolderPermissions),
                TextEntry::make('wrong_folder_permissions')
                    ->hiddenLabel()
                    ->state('The following Folders have wrong permissions: ' . implode(', ', array_keys($folderPermissions, false)))
                    ->visible(!$correctFolderPermissions),
            ]);

        return Step::make('requirements')
            ->label('Server Requirements')
            ->schema($fields)
            ->afterValidation(function () use ($correctPhpVersion, $allExtensionsInstalled, $correctFolderPermissions) {
                if (!$correctPhpVersion || !$allExtensionsInstalled || !$correctFolderPermissions) {
                    Notification::make()
                        ->title('Some requirements are missing!')
                        ->danger()
                        ->send();

                    throw new Halt('Some requirements are missing');
                }
            });
    }
}
