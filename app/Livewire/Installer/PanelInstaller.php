<?php

namespace App\Livewire\Installer;

use App\Livewire\Installer\Steps\CacheStep;
use App\Livewire\Installer\Steps\DatabaseStep;
use App\Livewire\Installer\Steps\EnvironmentStep;
use App\Livewire\Installer\Steps\QueueStep;
use App\Livewire\Installer\Steps\RequirementsStep;
use App\Livewire\Installer\Steps\SessionStep;
use App\Models\User;
use App\Services\Helpers\LanguageService;
use App\Services\Users\UserCreationService;
use App\Traits\CheckMigrationsTrait;
use App\Traits\EnvironmentWriterTrait;
use Exception;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\SimplePage;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Exceptions\Halt;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

/**
 * @property Schema $form
 */
class PanelInstaller extends SimplePage implements HasForms
{
    use CheckMigrationsTrait;
    use EnvironmentWriterTrait;
    use InteractsWithForms;

    /** @var array<string, mixed> */
    public array $data = [];

    protected string $view = 'filament.pages.installer';

    public function getTitle(): string
    {
        return trans('installer.title');
    }

    public function getMaxContentWidth(): Width|string
    {
        return Width::SevenExtraLarge;
    }

    public static function isInstalled(): bool
    {
        return config('app.installed');
    }

    public function mount(): void
    {
        abort_if(self::isInstalled(), 404);

        $this->form->fill();
    }

    /** @return Component[]
     * @throws Exception
     */
    protected function getFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    $this->getLanguageComponent(),
                ]),
            Wizard::make([
                RequirementsStep::make(),
                EnvironmentStep::make($this),
                DatabaseStep::make($this),
                CacheStep::make($this),
                QueueStep::make($this),
                SessionStep::make(),
            ])
                ->persistStepInQueryString()
                ->nextAction(function (Action $action) {
                    $action
                        ->label(trans('installer.next_step'))
                        ->keyBindings('enter');
                })
                ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                    <x-filament::button
                        type="submit"
                        size="sm"
                        wire:loading.attr="disabled"
                    >
                        {{ trans('installer.finish') }}
                        <span wire:loading><x-filament::loading-indicator class="h-4 w-4" /></span>
                    </x-filament::button>
                BLADE))),
        ];
    }

    protected function getLanguageComponent(): Component
    {
        return Select::make('language')
            ->hiddenLabel()
            ->prefix(trans('profile.language'))
            ->prefixIcon('tabler-flag')
            ->required()
            ->live()
            ->default('en')
            ->selectablePlaceholder(false)
            ->options(fn (LanguageService $languageService) => $languageService->getAvailableLanguages())
            ->afterStateUpdated(fn ($state, Application $app) => $app->setLocale($state ?? config('app.locale')))
            ->columnStart(4);
    }

    protected function getFormStatePath(): ?string
    {
        return 'data';
    }

    public function submit(UserCreationService $userCreationService): void
    {
        try {
            // Disable installer
            $this->writeToEnvironment(['APP_INSTALLED' => 'true']);

            // Run migrations
            $this->runMigrations();

            // Create admin user & login
            $user = $this->createAdminUser($userCreationService);
            auth()->guard()->login($user, true);

            // Write session data at the very end to avoid "page expired" errors
            $this->writeToEnv('env_session');

            // Redirect to admin panel
            $this->redirect(Filament::getPanel('admin')->getUrl());
        } catch (Halt) {
        }
    }

    public function writeToEnv(string $key): void
    {
        try {
            $variables = array_get($this->data, $key);
            $variables = array_filter($variables); // Filter array to remove NULL values
            $this->writeToEnvironment($variables);
        } catch (Exception $exception) {
            report($exception);

            Notification::make()
                ->title(trans('installer.exceptions.write_env'))
                ->body($exception->getMessage())
                ->danger()
                ->persistent()
                ->send();

            throw new Halt(trans('installer.exceptions.write_env'));
        }

        Artisan::call('config:clear');
    }

    public function runMigrations(): void
    {
        try {
            Artisan::call('migrate', [
                '--force' => true,
                '--seed' => true,
            ]);
        } catch (Exception $exception) {
            report($exception);

            Notification::make()
                ->title(trans('installer.database.exceptions.migration'))
                ->body($exception->getMessage())
                ->danger()
                ->persistent()
                ->send();

            throw new Halt(trans('installer.exceptions.migration'));
        }

        if (!$this->hasCompletedMigrations()) {
            Notification::make()
                ->title(trans('installer.database.exceptions.migration'))
                ->danger()
                ->persistent()
                ->send();

            throw new Halt(trans('installer.database.exceptions.migration'));
        }
    }

    public function createAdminUser(UserCreationService $userCreationService): User
    {
        try {
            $userData = array_get($this->data, 'user');
            $userData['root_admin'] = true;

            return $userCreationService->handle($userData);
        } catch (Exception $exception) {
            report($exception);

            Notification::make()
                ->title(trans('installer.exceptions.create_user'))
                ->body($exception->getMessage())
                ->danger()
                ->persistent()
                ->send();

            throw new Halt(trans('installer.exceptions.create_user'));
        }
    }
}
