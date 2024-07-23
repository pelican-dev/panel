<?php

namespace App\Filament\Pages\Installer;

use App\Filament\Pages\Installer\Steps\AdminUserStep;
use App\Filament\Pages\Installer\Steps\DatabaseStep;
use App\Filament\Pages\Installer\Steps\EnvironmentStep;
use App\Filament\Pages\Installer\Steps\RedisStep;
use App\Filament\Pages\Installer\Steps\RequirementsStep;
use App\Services\Users\UserCreationService;
use App\Traits\Commands\EnvironmentWriterTrait;
use Carbon\Carbon;
use Exception;
use Filament\Facades\Filament;
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
    use EnvironmentWriterTrait;
    use HasUnsavedDataChangesAlert;
    use InteractsWithForms;

    public $data = [];

    protected static string $view = 'filament.pages.installer';

    public function getMaxWidth(): MaxWidth|string
    {
        return MaxWidth::SevenExtraLarge;
    }

    public function mount()
    {
        if (is_installed()) {
            abort(404);
        }

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
                    >
                        Finish
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

            $redisUsed = count(collect($variables)->filter(function ($item) {
                return $item === 'redis';
            })) !== 0;

            // Create queue worker service (if needed)
            if ($variables['QUEUE_CONNECTION'] !== 'sync') {
                Artisan::call('p:environment:queue-service', [
                    '--use-redis' => $redisUsed,
                    '--overwrite' => true,
                ]);
            }

            // Run migrations
            Artisan::call('migrate', [
                '--force' => true,
                '--seed' => true,
            ]);

            // Create first admin user
            $userData = array_get($inputs, 'user');
            $userData['root_admin'] = true;
            app(UserCreationService::class)->handle($userData);

            // Install setup complete
            file_put_contents(storage_path('installed'), Carbon::now()->toDateTimeString());

            $this->rememberData();

            Notification::make()
                ->title('Successfully Installed')
                ->success()
                ->send();

            redirect()->intended(Filament::getUrl());
        } catch (Exception $exception) {
            Notification::make()
                ->title('Installation Failed')
                ->body($exception->getMessage())
                ->danger()
                ->send();
        }
    }
}
