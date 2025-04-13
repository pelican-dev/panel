<?php

namespace App\Features;

use App\Facades\Activity;
use App\Models\Permission;
use App\Models\Server;
use App\Repositories\Daemon\DaemonPowerRepository;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;

class JavaVersion extends Feature
{
    /** @return array<string> */
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

    public function action(): Action
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return Action::make($this->featureName())
            ->requiresConfirmation()
            ->modalHeading('Unsupported Java Version')
            ->modalDescription('This server is currently running an unsupported version of Java and cannot be started.')
            ->modalSubmitActionLabel('Update Docker Image')
            ->disabledForm(fn () => !auth()->user()->can(Permission::ACTION_STARTUP_DOCKER_IMAGE, $server))
            ->form([
                Placeholder::make('java')
                    ->label('Please select a supported version from the list below to continue starting the server.'),
                Select::make('image')
                    ->label('Docker Image')
                    ->visible(fn () => in_array($server->image, $server->egg->docker_images))
                    ->options(fn () => array_flip($server->egg->docker_images)),
            ])
            ->action(function (array $data, DaemonPowerRepository $powerRepository) use ($server) {
                /** @var Server $server */
                $server = Filament::getTenant();
                try {
                    $new = $data['image'];
                    $original = $server->image;
                    $server->forceFill(['image' => $new])->saveOrFail();

                    if ($original !== $server->image) {
                        Activity::event('server:startup.image')
                            ->property(['old' => $original, 'new' => $new])
                            ->log();
                    }
                    $powerRepository->setServer($server)->send('restart');

                    Notification::make()
                        ->title('Docker image updated')
                        ->body('Restart the server to use the new image.')
                        ->success()
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Error')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }
}
