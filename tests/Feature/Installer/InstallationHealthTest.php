<?php

use App\Checks\DatabaseExtensionCheck;
use App\Checks\PhpExtensionsCheck;
use App\Checks\PhpVersionCheck;
use App\Checks\WritablePathsCheck;
use App\Console\Commands\Environment\AppSettingsCommand;
use App\Enums\DatabaseDriver;
use App\Jobs\InstallEgg;
use App\Livewire\Installer\PanelInstaller;
use App\Models\Role;
use App\Models\User;
use App\Services\Environment\InstallationHealthService;
use Illuminate\Support\Facades\Queue;
use Spatie\Health\Checks\Result;
use Spatie\Health\Enums\Status;

covers(
    AppSettingsCommand::class,
    DatabaseDriver::class,
    DatabaseExtensionCheck::class,
    InstallationHealthService::class,
    PhpExtensionsCheck::class,
    PhpVersionCheck::class,
    WritablePathsCheck::class,
);

it('reports installer requirements with Spatie health results', function () {
    $results = app(InstallationHealthService::class)->systemRequirements();

    expect($results)->toHaveCount(3)
        ->and($results->every(fn (Result $result) => isset($result->check)))->toBeTrue();
});

it('fails the PHP check below the supported version', function () {
    $result = PhpVersionCheck::new()
        ->currentVersion('8.2.99')
        ->run();

    expect($result->status)->toEqual(Status::failed())
        ->and($result->meta['minimum'])->toBe(PhpVersionCheck::MINIMUM_VERSION);
});

it('fails when a required PHP extension is unavailable', function () {
    $result = PhpExtensionsCheck::new()
        ->requireExtensions(['pelican_missing_extension'])
        ->run();

    expect($result->status)->toEqual(Status::failed())
        ->and($result->getNotificationMessage())->toContain('pelican_missing_extension');
});

it('maps database drivers to their PDO extensions and default ports', function () {
    expect(DatabaseDriver::SQLite->requiredExtension())->toBe('pdo_sqlite')
        ->and(DatabaseDriver::SQLite->defaultPort())->toBeNull()
        ->and(DatabaseDriver::MariaDB->requiredExtension())->toBe('pdo_mysql')
        ->and(DatabaseDriver::MariaDB->defaultPort())->toBe(3306)
        ->and(DatabaseDriver::PostgreSQL->requiredExtension())->toBe('pdo_pgsql')
        ->and(DatabaseDriver::PostgreSQL->defaultPort())->toBe(5432);
});

it('rejects unsupported database driver command options', function () {
    $this->artisan('p:environment:database', ['--driver' => 'invalid'])
        ->expectsOutput('Unsupported database driver [invalid].')
        ->assertFailed();
});

it('checks SQLite database paths before installer configuration is written', function () {
    $health = app(InstallationHealthService::class);

    $valid = $health->databaseConnection(DatabaseDriver::SQLite, ['database' => ':memory:']);
    $invalid = $health->databaseConnection(DatabaseDriver::SQLite, [
        'database' => 'missing-directory/database.sqlite',
    ]);

    expect($valid->status)->toEqual(Status::ok())
        ->and($invalid->status)->toEqual(Status::failed());
});

it('reports unwritable installer paths', function () {
    $result = WritablePathsCheck::new()
        ->paths([base_path('pelican-path-that-does-not-exist')])
        ->run();

    expect($result->status)->toEqual(Status::failed())
        ->and($result->getNotificationMessage())->toContain('pelican-path-that-does-not-exist');
});

it('provides an explicit preflight bypass for emergency setup', function () {
    $command = app(AppSettingsCommand::class);

    expect($command->getDefinition()->hasOption('skip-preflight'))->toBeTrue();
});

it('runs the shared preflight from the command line', function () {
    $this->artisan('p:environment:preflight', ['--with-database' => true])
        ->assertSuccessful();
});

it('validates a complete installation after setup', function () {
    config()->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
    config()->set('app.installed', true);
    User::factory()->create()->syncRoles(Role::getRootAdmin());

    $this->artisan('p:environment:health')
        ->assertSuccessful();
});

it('keeps completed installer requests safely blocked on retries', function () {
    config()->set('app.installed', true);

    $this->get(route('installer'))->assertNotFound();
    $this->get(route('installer'))->assertNotFound();
});

it('can repeat installer migrations and egg dispatch without crashing', function () {
    Queue::fake();

    $installer = app(PanelInstaller::class);
    $installer->data = [
        'eggs' => [
            'minecraft' => ['https://example.com/egg.json'],
        ],
    ];

    $installer->runMigrations();
    $installer->runMigrations();
    $installer->installEggs();
    $installer->installEggs();

    Queue::assertPushed(InstallEgg::class, 2);
});
