<?php

namespace App\Filament\Pages\Installer;

use App\Filament\Pages\Installer\Steps\AdminUserStep;
use App\Filament\Pages\Installer\Steps\DatabaseStep;
use App\Filament\Pages\Installer\Steps\EnvironmentStep;
use App\Filament\Pages\Installer\Steps\RedisStep;
use App\Filament\Pages\Installer\Steps\RequirementsStep;
use App\Models\User;
use App\Services\Users\UserCreationService;
use App\Traits\CheckMigrationsTrait;
use App\Traits\EnvironmentWriterTrait;
use Exception;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\SimplePage;
use Filament\Support\Enums\MaxWidth;
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
    use HasUnsavedDataChangesAlert;
    use InteractsWithForms;

    public $data = [];

    protected static string $view = 'filament.pages.installer';

    public function getMaxWidth(): MaxWidth|string
    {
        return MaxWidth::SevenExtraLarge;
    }

    public static function show(): bool
    {
        if (User::count() <= 0) {
            return true;
        }

        if (config('panel.client_features.installer.enabled')) {
            return true;
        }

        return false;
    }

    public function mount()
    {
        abort_unless(self::show(), 404);

        $this->form->fill();
    }

    public function dehydrate(): void
    {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
    }

    protected function getFormSchema(): array
    {
        return [
            Wizard::make([
                RequirementsStep::make(),
                EnvironmentStep::make(),
                DatabaseStep::make(),
                RedisStep::make()
                    ->hidden(fn (Get $get) => $get('env.SESSION_DRIVER') != 'redis' && $get('env.QUEUE_CONNECTION') != 'redis' && $get('env.CACHE_STORE') != 'redis'),
                AdminUserStep::make(),
            ])
                ->persistStepInQueryString()
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

    protected function hasUnsavedDataChangesAlert(): bool
    {
        return true;
    }

    public function submit()
    {
        try {
            $inputs = $this->form->getState();

            // Write variables to .env file
            $variables = array_get($inputs, 'env');
            $this->writeToEnvironment($variables);

            // Clear config cache
            Artisan::call('config:clear');

            // Run migrations
            Artisan::call('migrate', [
                '--force' => true,
                '--seed' => true,
                '--database' => $variables['DB_CONNECTION'],
            ]);

            if (!$this->hasCompletedMigrations()) {
                throw new Exception('Migrations didn\'t run successfully. Double check your database configuration.');
            }

            // Create first admin user
            $userData = array_get($inputs, 'user');
            $userData['root_admin'] = true;
            $user = app(UserCreationService::class)->handle($userData);

            // Install setup complete
            $this->writeToEnvironment(['APP_INSTALLER' => 'false']);

            $this->rememberData();

            Notification::make()
                ->title('Successfully Installed')
                ->success()
                ->send();

            auth()->loginUsingId($user->id);

            return redirect('/admin');
        } catch (Exception $exception) {
            report($exception);

            Notification::make()
                ->title('Installation Failed')
                ->body($exception->getMessage())
                ->danger()
                ->persistent()
                ->send();
        }
    }
}
