<?php

namespace App\Livewire\Installer;

use App\Models\User;
use Filament\Forms\Form;
use Filament\Pages\SimplePage;
use Illuminate\Support\HtmlString;
use App\Traits\CheckMigrationsTrait;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Wizard;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Blade;
use App\Traits\EnvironmentWriterTrait;
use Filament\Forms\Contracts\HasForms;
use App\Filament\Admin\Pages\Dashboard;
use Illuminate\Support\Facades\Artisan;
use Filament\Notifications\Notification;
use App\Livewire\Installer\Steps\CacheStep;
use App\Livewire\Installer\Steps\QueueStep;
use App\Services\Users\UserCreationService;
use App\Livewire\Installer\Steps\SessionStep;
use Filament\Forms\Components\Actions\Action;
use App\Livewire\Installer\Steps\DatabaseStep;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Livewire\Installer\Steps\EnvironmentStep;
use App\Livewire\Installer\Steps\RequirementsStep;

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
        return config('app.installed');
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
        } catch (\Exception $exception) {
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
        } catch (\Exception $exception) {
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
        } catch (\Exception $exception) {
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
