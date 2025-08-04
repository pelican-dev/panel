<?php

namespace App\Console\Commands\Plugin;

use App\Enums\PluginStatus;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CreatePluginCommand extends Command
{
    protected $signature = 'p:plugin:create
                            {--name=}
                            {--author=}
                            {--description=}
                            {--category=}
                            {--url=}
                            {--updateUrl=}
                            {--panels=}
                            {--composerPackages=}';

    protected $description = 'Create a new plugin';

    public function __construct(private Filesystem $filesystem)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $name = $this->option('name') ?? $this->ask('Name');
        $id = str_slug(strtolower($name));

        if ($this->filesystem->exists(plugin_path($id))) {
            $this->error('Plugin with that name already exists!');

            return;
        }

        $author = $this->option('author') ?? $this->ask('author');

        $namespace = $author . '\\' . studly_case($name);
        $class = studly_case($name . 'Plugin');

        if (class_exists('\\' . $namespace . '\\' . $class)) {
            $this->error('Plugin class with that name already exists!');

            return;
        }

        $this->info('Creating Plugin "' . $name . '" (' . $id . ') by ' . $author);

        $description = $this->option('description') ?? $this->ask('Description');
        $category = $this->option('category') ?? $this->choice('Category', [
            'plugin' => 'Plugin',
            'theme' => 'Theme',
            'language' => 'Language Pack',
        ], 'plugin');
        $url = $this->option('url') ?? $this->ask('URL', 'https://github.com/' . $author . '/' . $id);
        $updateUrl = $this->option('updateUrl') ?? $this->ask('Update URL', 'https://raw.githubusercontent.com/' . $author . '/' . $id . '/refs/heads/main/update.json');
        $panels = $this->option('panels') ?? $this->choice('Panels', [
            'admin' => 'Admin Area',
            'server' => 'Client Area',
            'app' => 'Server List',
        ], 'admin,server', multiple: true);
        $composerPackages = $this->option('composerPackages') ?? $this->ask('Composer Packages');

        // Create base directory
        $this->filesystem->makeDirectory(plugin_path($id));

        // Write plugin.json
        $this->filesystem->put(plugin_path($id, 'plugin.json'), json_encode([
            'meta' => [
                'status' => PluginStatus::Enabled,
                'status_message' => null,
                'load_order' => 0,
            ],
            'id' => $id,
            'name' => $name,
            'author' => $author,
            'version' => '1.0.0',
            'description' => $description,
            'category' => $category,
            'url' => $url,
            'update_url' => $updateUrl,
            'namespace' => $namespace,
            'class' => $class,
            'panels' => !is_array($panels) ? explode(',', $panels) : $panels,
            'panel_version' => config('app.version') === 'canary' ? null : config('app.version'),
            'composer_packages' => !is_array($composerPackages) ? explode(',', $composerPackages) : $composerPackages,
        ], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // Create src directory and create main class
        $this->filesystem->makeDirectory(plugin_path($id, 'src'));
        $this->filesystem->put(plugin_path($id, 'src', $class . '.php'), str_replace(['$namespace$', '$class$', '$id$'], [$namespace, $class, $id], file_get_contents(__DIR__ . '/Plugin.stub')));

        // Create Providers directory and create service provider
        $this->filesystem->makeDirectory(plugin_path($id, 'src', 'Providers'));
        $this->filesystem->put(plugin_path($id, 'src', 'Providers', $class . 'Provider.php'), str_replace(['$namespace$', '$class$'], [$namespace, $class], file_get_contents(__DIR__ . '/PluginProvider.stub')));

        // Create config directory and create config file
        $this->filesystem->makeDirectory(plugin_path($id, 'config'));
        $this->filesystem->put(plugin_path($id, 'config', $id . '.php'), str_replace(['$name$'], [$name], file_get_contents(__DIR__ . '/PluginConfig.stub')));

        $this->info('Plugin created.');
    }
}
