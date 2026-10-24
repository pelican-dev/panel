<?php

use App\Enums\PluginStatus;
use App\Models\Plugin;
use App\Services\Helpers\PluginService;
use Illuminate\Support\Facades\File;

afterEach(function () {
    File::deleteDirectory(plugin_path('test-loader-crash'));
    Plugin::refreshRows();
});

it('keeps a plugin crashed when the loader catches its boot error', function () {
    File::ensureDirectoryExists(plugin_path('test-loader-crash', 'config'));
    File::put(plugin_path('test-loader-crash', 'plugin.json'), json_encode([
        'id' => 'test-loader-crash',
        'name' => 'Test Loader Crash',
        'author' => 'Pelican',
        'version' => '1.0.0',
        'category' => 'plugin',
        'namespace' => 'TestLoaderCrash',
        'class' => 'TestLoaderCrash',
        'meta' => ['status' => PluginStatus::Enabled->value],
    ]));
    File::put(plugin_path('test-loader-crash', 'config', 'test-loader-crash.php'), '<?php throw new Exception("broken config");');
    Plugin::refreshRows();
    // Leave any other installed plugins alone.
    Plugin::where('id', '!=', 'test-loader-crash')->delete();

    $booting = Mockery::mock($this->app);
    $booting->shouldReceive('isBooted')->andReturnFalse();
    $booting->shouldReceive('runningUnitTests')->andReturnFalse();
    $service = new PluginService($booting);
    $this->app->instance(PluginService::class, $service);

    $service->loadPlugins();
    expect(File::json(plugin_path('test-loader-crash', 'plugin.json'))['meta']['status'])->toBe(PluginStatus::Crashed->value);

    // The next request skips it instead of requiring the config again.
    $service->loadPlugins();
    expect(File::json(plugin_path('test-loader-crash', 'plugin.json'))['meta']['status'])->toBe(PluginStatus::Crashed->value);
});
