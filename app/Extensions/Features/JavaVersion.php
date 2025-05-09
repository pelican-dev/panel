<?php

namespace App\Extensions\Features;

use App\Facades\Activity;
use App\Models\Permission;
use App\Models\Server;
use App\Repositories\Daemon\DaemonPowerRepository;
use Exception;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Application;

class JavaVersion extends FeatureProvider
{
    public function __construct(protected Application $app)
    {
        parent::__construct($app);
    }

    /** @return array<string> */
    public function getListeners(): array
    {
        return [
            'java.lang.UnsupportedClassVersionError',
            'unsupported major.minor version',
            'has been compiled by a more recent version of the java runtime',
            'minecraft 1.17 requires running the server with java 16 or above',
            'minecraft 1.18 requires running the server with java 17 or above',
            'minecraft 1.19 requires running the server with java 17 or above',
        ];
    }

    public function getId(): string
    {
        return 'java_version';
    }

    public function getAction(): Action
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return Action::make($this->getId())
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
                    ->disabled(fn () => !in_array($server->image, $server->egg->docker_images))
                    ->options(fn () => collect($server->egg->docker_images)->mapWithKeys(fn ($key, $value) => [$key => $value]))
                    ->selectablePlaceholder(false)
                    ->default(fn () => $server->image)
                    ->notIn(fn () => $server->image)
                    ->required()
                    ->preload()
                    ->native(false),
            ])
            ->action(function (array $data, DaemonPowerRepository $powerRepository) use ($server) {
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
                        ->body('Server will restart now.')
                        ->success()
                        ->send();
                } catch (Exception $exception) {
                    Notification::make()
                        ->title('Could not update docker image')
                        ->body($exception->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }

    public static function register(Application $app): self
    {
        return new self($app);
    }
}
