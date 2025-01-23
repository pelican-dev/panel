<?php

namespace App\Features;

use App\Repositories\Daemon\DaemonFileRepository;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;

class MinecraftEula extends Feature
{
    public function listeners(): array
    {
        return [
            'you need to agree to the eula in order to run the server',
        ];
    }

    public function featureName(): string
    {
        return 'eula';
    }

    public function modal(): Form
    {
        return $this->makeForm()
            ->schema([
                Placeholder::make('By pressing "I Accept" below you are indicating your agreement to the Minecraft EULA'),
                Actions::make([
                    Actions\Action::make('closeModal')
                        ->label('Close')
                        ->color('secondary')
                        ->extraAttributes([
                            'x-on:click' => 'isOpen = false',  // close modal [FASTER]
                        ]),
                    Actions\Action::make('acceptEula')
                        ->label('Save')
                        ->color('primary')
                        ->extraAttributes([
                            // 'x-on:click' => '$dispatch("minecraft-eula-accept")',  // close modal [FASTER]
                        ])
                        ->action(function (DaemonFileRepository $fileRepository) {
                            try {
                                dd('success');
                                $fileRepository->putContent('eula.txt', 'eula=true');
                            } catch (\Exception $e) {
                                dd($e);
                                Notification::make()
                                    ->title('Error')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                ])
                    ->fullWidth()->label('Minecraft EULA'),
            ]);
    }

    public function field(): Field
    {
        return CustomModal::make('modal-eula')
            ->heading('Minecraft EULA')
            ->description('By pressing "I Accept" below you are indicating your agreement to the Minecraft EULA')
            ->registerActions([

                Action::make($this->featureName())
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
                    ),
            ]);
    }

    public function action(): Action
    {
        return Action::make($this->featureName())
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
