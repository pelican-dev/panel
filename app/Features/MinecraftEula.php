<?php

namespace App\Features;

use App\Models\Server;
use App\Repositories\Daemon\DaemonFileRepository;
use Filament\Facades\Filament;
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

    public function action(): Action
    {
        return Action::make($this->featureName())
            ->requiresConfirmation()
            ->modalHeading('Minecraft EULA')
            ->modalDescription('By pressing "I Accept" below you are indicating your agreement to the Minecraft EULA')
            ->modalSubmitActionLabel('I Accept')
            ->action(function (Action $action, DaemonFileRepository $fileRepository) {
                try {
                    /** @var Server $server */
                    $server = Filament::getTenant();
                    $fileRepository->setServer($server)->putContent('eula.txt', 'eula=true');
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
