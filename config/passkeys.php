<?php

return [
    /*
     * After a successful authentication attempt using a passkey
     * we'll redirect to this URL.
     */
    'redirect_to_after_login' => '/',

    /*
     * These class are responsible for performing core tasks regarding passkeys.
     * You can customize them by creating a class that extends the default, and
     * by specifying your custom class name here.
     */
    'actions' => [
        'generate_passkey_register_options' => Spatie\LaravelPasskeys\Actions\GeneratePasskeyRegisterOptionsAction::class,
        'store_passkey' => Spatie\LaravelPasskeys\Actions\StorePasskeyAction::class,
        'generate_passkey_authentication_options' => \Spatie\LaravelPasskeys\Actions\GeneratePasskeyAuthenticationOptionsAction::class,
        'find_passkey' => Spatie\LaravelPasskeys\Actions\FindPasskeyToAuthenticateAction::class,
        'configure_ceremony_step_manager_factory' => Spatie\LaravelPasskeys\Actions\ConfigureCeremonyStepManagerFactoryAction::class,
    ],

    /*
     * These properties will be used to generate the passkey.
     */
    'relying_party' => [
        'name' => config('app.name'),
        'id' => parse_url(config('app.url'), PHP_URL_HOST),
        'icon' => null,
    ],

    /*
     * The models used by the package.
     *
     * You can override this by specifying your own models
     */
    'models' => [
        'passkey' => Spatie\LaravelPasskeys\Models\Passkey::class,
        'authenticatable' => env('AUTH_MODEL', App\Models\User::class),
    ],
];
