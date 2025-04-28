<?php

namespace App\Extensions\Features;

use App\Models\Server;
use App\Repositories\Daemon\DaemonFileRepository;
use App\Repositories\Daemon\DaemonPowerRepository;
use Exception;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class MinecraftEula extends FeatureProvider
{
    public function __construct(protected Application $app)
    {
        parent::__construct($app);
    }

    /** @return array<string> */
    public function getListeners(): array
    {
        return [
            'You need to agree to the EULA in order to run the server',
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
            ->modalDescription(new HtmlString(Blade::render('By pressing "I Accept" below you are indicating your agreement to the <x-filament::link href="https://minecraft.net/eula" target="_blank">Minecraft EULA </x-filament::link>')))
            ->modalSubmitActionLabel('I Accept')
            ->action(function (DaemonFileRepository $fileRepository, DaemonPowerRepository $powerRepository) {
                try {
                    /** @var Server $server */
                    $server = Filament::getTenant();
                    $content = $fileRepository->setServer($server)->getContent('eula.txt');
                    $content = preg_replace('/(eula=)false/', '\1true', $content);
                    $fileRepository->setServer($server)->putContent('eula.txt', $content);
                    $powerRepository->setServer($server)->send('restart');

                    Notification::make()
                        ->title('Docker image updated')
                        ->body('Restart the server.')
                        ->success()
                        ->send();
                } catch (Exception $e) {
                    Notification::make()
                        ->title('Error')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            }
            );
    }

    public static function register(Application $app): self
    {
        return new self($app);
    }
}
