<?php

namespace App\Tests\Integration\Installer;

use App\Livewire\Installer\PanelInstaller;
use App\Tests\Integration\IntegrationTestCase;
use Livewire\Livewire;

class PanelInstallerTest extends IntegrationTestCase
{
    public function test_sqlite_defaults_remain_relative_and_do_not_include_connection_fields(): void
    {
        config()->set('app.installed', false);
        config()->set('database.default', 'sqlite');

        $component = Livewire::test(PanelInstaller::class);

        $component
            ->assertSet('data.env_database.DB_CONNECTION', 'sqlite')
            ->assertSet('data.env_database.DB_DATABASE', 'database.sqlite')
            ->assertSet('data.env_database.DB_HOST', null)
            ->assertSet('data.env_database.DB_PORT', null)
            ->assertSet('data.env_database.DB_USERNAME', null)
            ->assertSet('data.env_database.DB_PASSWORD', null);

        $component->assertSuccessful();
    }

    public function test_database_fields_are_hydrated_from_a_preconfigured_non_sqlite_connection(): void
    {
        config()->set('app.installed', false);
        config()->set('database.default', 'mariadb');
        config()->set('database.connections.mariadb', [
            'driver' => 'mariadb',
            'host' => 'pelican-db',
            'port' => '3307',
            'database' => 'pelican',
            'username' => 'pelican-user',
            'password' => 'pelican-password',
        ]);

        $component = Livewire::test(PanelInstaller::class);

        $component
            ->assertSet('data.env_database.DB_CONNECTION', 'mariadb')
            ->assertSet('data.env_database.DB_HOST', 'pelican-db')
            ->assertSet('data.env_database.DB_PORT', '3307')
            ->assertSet('data.env_database.DB_DATABASE', 'pelican')
            ->assertSet('data.env_database.DB_USERNAME', 'pelican-user')
            ->assertSet('data.env_database.DB_PASSWORD', null);

        $component
            ->set('data.env_database.DB_CONNECTION', 'sqlite')
            ->set('data.env_database.DB_CONNECTION', 'mariadb')
            ->assertSet('data.env_database.DB_HOST', 'pelican-db')
            ->assertSet('data.env_database.DB_PORT', '3307')
            ->assertSet('data.env_database.DB_DATABASE', 'pelican')
            ->assertSet('data.env_database.DB_USERNAME', 'pelican-user')
            ->assertSet('data.env_database.DB_PASSWORD', null);

        $component->assertSuccessful();
    }
}
