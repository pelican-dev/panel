<?php

namespace App\Filament\Pages;

use App\Notifications\MailTested;
use App\Traits\Commands\EnvironmentWriterTrait;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification as MailNotification;

/**
 * @property Form $form
 */
class Settings extends Page implements HasForms
{
    use EnvironmentWriterTrait;
    use HasUnsavedDataChangesAlert;
    use InteractsWithFormActions;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'tabler-settings';
    protected static ?string $navigationGroup = 'Advanced';

    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Tabs::make('Tabs')
                ->columns(2)
                ->persistTabInQueryString()
                ->tabs([
                    Tab::make('general')
                        ->label('General')
                        ->icon('tabler-home')
                        ->schema([
                            TextInput::make('APP_NAME')
                                ->label('App Name')
                                ->required(true)
                                ->default(env('APP_NAME', 'Pelican')),
                            ToggleButtons::make('FILAMENT_TOP_NAVIGATION')
                                ->label('Navigation')
                                ->grouped()
                                ->options([
                                    false => 'Sidebar',
                                    true => 'Topbar',
                                ])
                                ->default(env('FILAMENT_TOP_NAVIGATION', config('panel.filament.top-navigation'))),
                            ToggleButtons::make('PANEL_USE_BINARY_PREFIX')
                                ->label('Unit prefix')
                                ->grouped()
                                ->options([
                                    false => 'Decimal Prefix (MB/ GB)',
                                    true => 'Binary Prefix (MiB/ GiB)',
                                ])
                                ->default(env('PANEL_USE_BINARY_PREFIX', config('panel.use_binary_prefix'))),
                        ]),
                    Tab::make('recaptcha')
                        ->label('reCAPTCHA')
                        ->icon('tabler-shield')
                        ->schema([
                            Toggle::make('RECAPTCHA_ENABLED')
                                ->label('Status')
                                ->onIcon('tabler-check')
                                ->offIcon('tabler-x')
                                ->onColor('success')
                                ->offColor('danger')
                                ->live()
                                ->default(env('RECAPTCHA_ENABLED', config('recaptcha.enabled'))),
                            TextInput::make('RECAPTCHA_DOMAIN')
                                ->label('Domain')
                                ->required(true)
                                ->visible(fn (Get $get) => $get('RECAPTCHA_ENABLED'))
                                ->default(env('RECAPTCHA_DOMAIN', config('recaptcha.domain'))),
                            TextInput::make('RECAPTCHA_WEBSITE_KEY')
                                ->label('Website Key')
                                ->required(true)
                                ->visible(fn (Get $get) => $get('RECAPTCHA_ENABLED'))
                                ->default(env('RECAPTCHA_WEBSITE_KEY', config('recaptcha.website_key'))),
                            TextInput::make('RECAPTCHA_SECRET_KEY')
                                ->label('Secret Key')
                                ->required(true)
                                ->visible(fn (Get $get) => $get('RECAPTCHA_ENABLED'))
                                ->default(env('RECAPTCHA_SECRET_KEY', config('recaptcha.secret_key'))),
                        ]),
                    Tab::make('mail')
                        ->label('Mail')
                        ->icon('tabler-mail')
                        ->schema([
                            Select::make('MAIL_MAILER')
                                ->label('Mail Driver')
                                ->columnSpanFull()
                                ->options([
                                    'log' => 'Print mails to Log',
                                    'smtp' => 'SMTP Server',
                                    'sendmail' => 'sendmail Binary',
                                    'mailgun' => 'Mailgun',
                                    'mandrill' => 'Mandrill',
                                    'postmark' => 'Postmark',
                                ])
                                ->live()
                                ->default(env('MAIL_MAILER', 'log'))
                                ->hintAction(
                                    FormAction::make('test')
                                        ->label('Send Test Mail')
                                        ->icon('tabler-send')
                                        ->hidden(fn (Get $get) => $get('MAIL_MAILER') === 'log')
                                        ->action(function () {
                                            try {
                                                MailNotification::route('mail', auth()->user()->email)
                                                    ->notify(new MailTested(auth()->user()));

                                                Notification::make()
                                                    ->title('Test Mail sent')
                                                    ->success()
                                                    ->send();
                                            } catch (Exception $exception) {
                                                Notification::make()
                                                    ->title('Test Mail failed')
                                                    ->body($exception->getMessage())
                                                    ->danger()
                                                    ->send();
                                            }
                                        })
                                ),
                            TextInput::make('MAIL_FROM_ADDRESS')
                                ->label('From Address')
                                ->required(true)
                                ->email()
                                ->default(env('MAIL_FROM_ADDRESS', 'no-reply@example.com')),
                            TextInput::make('MAIL_FROM_NAME')
                                ->label('From Name')
                                ->required(true)
                                ->default(env('MAIL_FROM_NAME', 'Pelican Admin')),
                            TextInput::make('MAIL_HOST')
                                ->label('SMTP Host')
                                ->required(true)
                                ->visible(fn (Get $get) => $get('MAIL_MAILER') === 'smtp')
                                ->default(env('MAIL_HOST', 'smtp.example.com')),
                            TextInput::make('MAIL_PORT')
                                ->label('SMTP Port')
                                ->required(true)
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(65535)
                                ->visible(fn (Get $get) => $get('MAIL_MAILER') === 'smtp')
                                ->default(env('MAIL_PORT', 25)),
                            TextInput::make('MAIL_USERNAME')
                                ->label('SMTP Username')
                                ->required(true)
                                ->visible(fn (Get $get) => $get('MAIL_MAILER') === 'smtp')
                                ->default(env('MAIL_USERNAME')),
                            TextInput::make('MAIL_PASSWORD')
                                ->label('SMTP Password')
                                ->password()
                                ->revealable()
                                ->visible(fn (Get $get) => $get('MAIL_MAILER') === 'smtp')
                                ->default(env('MAIL_PASSWORD')),
                            ToggleButtons::make('MAIL_ENCRYPTION')
                                ->label('SMTP encryption')
                                ->required(true)
                                ->grouped()
                                ->options(['tls' => 'TLS', 'ssl' => 'SSL', '' => 'None'])
                                ->visible(fn (Get $get) => $get('MAIL_MAILER') === 'smtp')
                                ->default(env('MAIL_ENCRYPTION', 'tls')),
                        ]),
                    Tab::make('misc')
                        ->label('Misc')
                        ->icon('tabler-tool')
                        ->schema([
                            Toggle::make('PANEL_CLIENT_ALLOCATIONS_ENABLED')
                                ->label('Automatic Allocation Creation')
                                ->onIcon('tabler-check')
                                ->offIcon('tabler-x')
                                ->onColor('success')
                                ->offColor('danger')
                                ->live()
                                ->columnSpanFull()
                                ->default(env('PANEL_CLIENT_ALLOCATIONS_ENABLED', config('panel.client_features.allocations.enabled'))),
                            TextInput::make('PANEL_CLIENT_ALLOCATIONS_RANGE_START')
                                ->label('Starting Port')
                                ->required(true)
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(65535)
                                ->visible(fn (Get $get) => $get('PANEL_CLIENT_ALLOCATIONS_ENABLED'))
                                ->default(env('PANEL_CLIENT_ALLOCATIONS_RANGE_START')),
                            TextInput::make('PANEL_CLIENT_ALLOCATIONS_RANGE_END')
                                ->label('Ending Port')
                                ->required(true)
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(65535)
                                ->visible(fn (Get $get) => $get('PANEL_CLIENT_ALLOCATIONS_ENABLED'))
                                ->default(env('PANEL_CLIENT_ALLOCATIONS_RANGE_END')),
                        ]),
                ]),
        ];
    }

    protected function getFormStatePath(): ?string
    {
        return 'data';
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            $this->writeToEnvironment($data);

            Artisan::call('config:clear');
            Artisan::call('queue:restart');

            $this->rememberData();

            $this->redirect($this->getUrl());

            Notification::make()
                ->title('Settings saved')
                ->success()
                ->send();
        } catch (Exception $exception) {
            Notification::make()
                ->title('Save failed')
                ->body($exception->getMessage())
                ->danger()
                ->send();
        }
    }

    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->submit('save')
                ->keyBindings(['mod+s']),
        ];
    }
}
