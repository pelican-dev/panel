<?php

namespace App\Livewire\Installer;

use App\Filament\Admin\Pages\Dashboard;
use App\Livewire\Installer\Steps\CacheStep;
use App\Livewire\Installer\Steps\DatabaseStep;
use App\Livewire\Installer\Steps\EnvironmentStep;
use App\Livewire\Installer\Steps\QueueStep;
use App\Livewire\Installer\Steps\RequirementsStep;
use App\Livewire\Installer\Steps\SessionStep;
use App\Models\User;
use App\Services\Users\UserCreationService;
use App\Traits\CheckMigrationsTrait;
use App\Traits\EnvironmentWriterTrait;
use Exception;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SimplePage;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

/**
 * @property Form $form
 */
class PanelInstaller extends SimplePage implements HasForms
{
    use CheckMigrationsTrait;
    use EnvironmentWriterTrait;
    use InteractsWithForms;

    public array $data = [];

    protected static string $view = 'filament.pages.installer';

    public function getMaxWidth(): MaxWidth|string
    {
        return MaxWidth::SevenExtraLarge;
    }

    public static function isInstalled(): bool
    {
        // This defaults to true so existing panels count as "installed"
        return env('APP_INSTALLED', true);
    }

    public function mount(): void
    {
        abort_if(self::isInstalled(), 404);

        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Wizard::make([
                RequirementsStep::make(),
                EnvironmentStep::make($this),
                DatabaseStep::make($this),
                CacheStep::make($this),
                QueueStep::make($this),
                SessionStep::make(),
            ])
                ->persistStepInQueryString()
                ->nextAction(fn (Action $action) => $action->keyBindings('enter'))
                ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                    <x-filament::button
                        type="submit"
                        size="sm"
                        wire:loading.attr="disabled"
                    >
                        Finish
                        <span wire:loading><x-filament::loading-indicator class="h-4 w-4" /></span>
                    </x-filament::button>
                BLADE))),
        ];
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
            $this->redirect(Dashboard::getUrl());
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
                ->title('Could not write to .env file')
                ->body($exception->getMessage())
                ->danger()
                ->persistent()
                ->send();

            throw new Halt('Error while writing .env file');
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
                ->title('Migrations failed')
                ->body($exception->getMessage())
                ->danger()
                ->persistent()
                ->send();

            throw new Halt('Error while running migrations');
        }

        if (!$this->hasCompletedMigrations()) {
            Notification::make()
                ->title('Migrations failed')
                ->danger()
                ->persistent()
                ->send();

            throw new Halt('Migrations failed');
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
                ->title('Could not create admin user')
                ->body($exception->getMessage())
                ->danger()
                ->persistent()
                ->send();

            throw new Halt('Error while creating admin user');
        }
    }
}
