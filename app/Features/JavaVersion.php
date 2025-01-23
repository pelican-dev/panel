<?php

namespace App\Features;

use App\Repositories\Daemon\DaemonFileRepository;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;

class JavaVersion extends Feature
{
    public function listeners(): array
    {
        return [
            'minecraft 1.17 requires running the server with java 16 or above',
            'minecraft 1.18 requires running the server with java 17 or above',
            'java.lang.unsupportedclassversionerror',
            'unsupported major.minor version',
            'has been compiled by a more recent version of the java runtime',
        ];
    }

    public function featureName(): string
    {
        return 'java_version';
    }

    public function modal(): Form
    {
        return $this->makeForm()
            ->schema([
                Placeholder::make('see me bitches'),
                TextInput::make('name'),
                Actions::make([
                    Actions\Action::make('closeUserModal')
                        ->label('Close')
                        ->color('secondary')
                        ->extraAttributes([
                            'x-on:click' => 'isOpen = false',  // close modal [FASTER]
                        ]),
                    Actions\Action::make('saveUserModal')
                        ->label('Save')
                        ->color('primary')
                        ->action(function (Get $get) {
                            logger($get('name'));
                        }),
                ])->fullWidth(),
            ]);
    }

    public function action(): Action
    {
        return Action::make('eula')
            ->form([
                Placeholder::make('eula')
                    ->label('By pressing I Accept below you are indicating your agreement to the Minecraft® EULA.'),
            ])
            ->action(function (DaemonFileRepository $fileRepository) {
                try {
                    $fileRepository->putContent('eula.txt', 'eula=true');
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Error')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            }
            );
    }
}
