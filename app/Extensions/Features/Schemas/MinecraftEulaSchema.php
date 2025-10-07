<?php

namespace App\Extensions\Features\Schemas;

use App\Extensions\Features\FeatureSchemaInterface;
use App\Models\Server;
use App\Repositories\Daemon\DaemonFileRepository;
use App\Repositories\Daemon\DaemonServerRepository;
use Exception;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class MinecraftEulaSchema implements FeatureSchemaInterface
{
    /** @return array<string> */
    public function getListeners(): array
    {
        return [
            'you need to agree to the eula in order to run the server',
        ];
    }

    public function getId(): string
    {
        return 'eula';
    }

    public function getAction(): Action
    {
        return Action::make($this->getId())
            ->requiresConfirmation()
            ->modalHeading('Minecraft EULA')
            ->modalDescription(new HtmlString(Blade::render('By pressing "I Accept" below you are indicating your agreement to the <x-filament::link href="https://minecraft.net/eula" target="_blank">Minecraft EULA </x-filament::link>.')))
            ->modalSubmitActionLabel('I Accept')
            ->action(function (DaemonFileRepository $fileRepository, DaemonServerRepository $serverRepository) {
                try {
                    /** @var Server $server */
                    $server = Filament::getTenant();

                    $fileRepository->setServer($server)->putContent('eula.txt', 'eula=true');

                    $serverRepository->setServer($server)->power('restart');

                    Notification::make()
                        ->title('Minecraft EULA accepted')
                        ->body('Server will restart now.')
                        ->success()
                        ->send();
                } catch (Exception $exception) {
                    Notification::make()
                        ->title('Could not accept Minecraft EULA')
                        ->body($exception->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }
}
