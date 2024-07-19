<?php

namespace App\Filament\Pages\Installer\Steps;

use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;

class RequirementsStep
{
    public static function make(): Step
    {
        $phpVersion = version_compare(PHP_VERSION, '8.2.0') >= 0;

        $fields = [
            self::makeToggle('php_version')
                ->label('PHP Version 8.2/ 8.3')
                ->default($phpVersion)
                ->validationMessages([
                    'accepted' => 'Your PHP Version needs to be 8.2 or 8.3.',
                ]),
        ];

        $phpExtensions = [
            'GD' => extension_loaded('gd'),
            'MySQL' => extension_loaded('pdo_mysql'),
            'mbstring' => extension_loaded('mbstring'),
            'BCMath' => extension_loaded('bcmath'),
            'XML' => extension_loaded('xml'),
            'cURL' => extension_loaded('curl'),
            'Zip' => extension_loaded('zip'),
            'intl' => extension_loaded('intl'),
            'SQLite3' => extension_loaded('pdo_sqlite'),
            'FPM' => extension_loaded('fpm'),
        ];

        foreach ($phpExtensions as $extension => $loaded) {
            $fields[] = self::makeToggle('ext_' . strtolower($extension))
                ->label($extension . ' Extension')
                ->default($loaded)
                ->validationMessages([
                    'accepted' => 'The ' . $extension . ' extension needs to be installed and enabled.',
                ]);
        }

        $folderPermissions = [
            'Storage' => substr(sprintf('%o', fileperms(base_path('storage/'))), -4),
            'Cache' => substr(sprintf('%o', fileperms(base_path('bootstrap/cache/'))), -4),
        ];

        foreach ($folderPermissions as $folder => $permission) {
            $correct = $permission >= 755;
            $fields[] = self::makeToggle('folder_' . strtolower($folder))
                ->label($folder . ' Folder writeable')
                ->default($correct)
                ->validationMessages([
                    'accepted' => 'The ' . $folder . ' needs to be writable. (755)',
                ]);
        }

        return Step::make('requirements')
            ->label('Server Requirements')
            ->schema($fields);
    }

    private static function makeToggle(string $name): Toggle
    {
        return Toggle::make($name)
            ->required()
            ->accepted()
            ->disabled()
            ->onIcon('tabler-check')
            ->offIcon('tabler-x')
            ->onColor('success')
            ->offColor('danger');
    }
}
