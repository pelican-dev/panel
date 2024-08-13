<?php

namespace App\Console\Commands\Plugin;

use App\Enums\PluginStatus;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CreatePluginCommand extends Command
{
    protected $signature = 'p:plugin:create
                            {name}
                            {author}
                            {--description=}
                            {--url=}
                            {--panel=}
                            {--category=}';

    protected $description = 'Create a new plugin';

    public function __construct(private Filesystem $filesystem)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $name = $this->argument('name');
        $id = str_slug(strtolower($name));

        if ($this->filesystem->exists(plugin_path($id))) {
            $this->error('Plugin with that name already exists!');

            return;
        }

        $author = $this->argument('author');

        $namespace = $author . '\\' . studly_case($name);
        $class = studly_case($name . 'Plugin');

        if (class_exists('\\' . $namespace . '\\' . $class)) {
            $this->error('Plugin class with that name already exists!');

            return;
        }

        $this->info('Creating Plugin "' . $name . '" (' . $id . ') by ' . $author);

        $description = $this->option('description') ?? $this->ask('Description');
        $url = $this->option('url') ?? $this->ask('URL', 'https://github.com/' . $author . '/' . $id);
        $panel = $this->option('panel') ?? $this->choice('Panel', [
            'admin' => 'Admin Area',
            'app' => 'Client Area',
            'both' => 'Both',
        ], 'admin');
        $category = $this->option('category') ?? $this->choice('Category', [
            'plugin' => 'Plugin',
            'theme' => 'Theme',
            'language' => 'Language Pack',
        ], 'plugin');

        // Create base directory
        $this->filesystem->makeDirectory(plugin_path($id));

        // Write plugin.json
        $this->filesystem->put(plugin_path($id, 'plugin.json'), json_encode([
            'id' => $id,
            'name' => $name,
            'author' => $author,
            'version' => '1.0.0',
            'description' => $description,
            'url' => $url,
            'namespace' => $namespace,
            'class' => $class,
            'status' => PluginStatus::Enabled,
            'status_message' => null,
            'panel' => $panel,
            'panel_version' => config('app.version') === 'canary' ? null : config('app.version'),
            'category' => $category,
        ], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // Create src directory and create main class
        $this->filesystem->makeDirectory(plugin_path($id, 'src'));
        $this->filesystem->put(plugin_path($id, 'src', $class . '.php'), str_replace(['$namespace$', '$class$', '$id$'], [$namespace, $class, $id], file_get_contents(__DIR__ . '/Plugin.stub')));

        // Create provider directory and create service provider
        $this->filesystem->makeDirectory(plugin_path($id, 'src', 'Providers'));
        $this->filesystem->put(plugin_path($id, 'src', 'Providers', $class . 'Provider.php'), str_replace(['$namespace$', '$class$'], [$namespace, $class], file_get_contents(__DIR__ . '/PluginProvider.stub')));

        $this->info('Plugin created.');
    }
}
