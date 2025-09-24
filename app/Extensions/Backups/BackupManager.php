<?php

namespace App\Extensions\Backups;

use App\Extensions\Filesystem\S3Filesystem;
use Aws\S3\S3Client;
use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\InMemory\InMemoryFilesystemAdapter;
use Webmozart\Assert\Assert;

class BackupManager
{
    /**
     * The array of resolved backup drivers.
     *
     * @var array<string, FilesystemAdapter>
     */
    protected array $adapters = [];

    /**
     * The registered custom driver creators.
     *
     * @var array<string, callable>
     */
    protected array $customCreators;

    public function __construct(protected Application $app) {}

    /**
     * Returns a backup adapter instance.
     */
    public function adapter(?string $name = null): FilesystemAdapter
    {
        return $this->get($name ?: $this->getDefaultAdapter());
    }

    /**
     * Set the given backup adapter instance.
     */
    public function set(string $name, FilesystemAdapter $disk): self
    {
        $this->adapters[$name] = $disk;

        return $this;
    }

    /**
     * Gets a backup adapter.
     */
    protected function get(string $name): FilesystemAdapter
    {
        return $this->adapters[$name] = $this->resolve($name);
    }

    /**
     * Resolve the given backup disk.
     */
    protected function resolve(string $name): FilesystemAdapter
    {
        $config = $this->getConfig($name);

        if (empty($config['adapter'])) {
            throw new InvalidArgumentException("Backup disk [$name] does not have a configured adapter.");
        }

        $adapter = $config['adapter'];

        if (isset($this->customCreators[$name])) {
            return $this->callCustomCreator($config);
        }

        $adapterMethod = 'create' . Str::studly($adapter) . 'Adapter';
        if (method_exists($this, $adapterMethod)) {
            $instance = $this->{$adapterMethod}($config);

            Assert::isInstanceOf($instance, FilesystemAdapter::class);

            return $instance;
        }

        throw new InvalidArgumentException("Adapter [$adapter] is not supported.");
    }

    /**
     * Calls a custom creator for a given adapter type.
     *
     * @param  array{adapter: string}  $config
     */
    protected function callCustomCreator(array $config): mixed
    {
        return $this->customCreators[$config['adapter']]($this->app, $config);
    }

    /**
     * Creates a new daemon adapter.
     *
     * @param  array<string, string>  $config
     */
    public function createWingsAdapter(array $config): FilesystemAdapter
    {
        return new InMemoryFilesystemAdapter();
    }

    /**
     * Creates a new S3 adapter.
     *
     * @param  array<string, string>  $config
     */
    public function createS3Adapter(array $config): FilesystemAdapter
    {
        $config['version'] = 'latest';

        if (!empty($config['key']) && !empty($config['secret'])) {
            $config['credentials'] = Arr::only($config, ['key', 'secret', 'token']);
        }

        $client = new S3Client($config);

        return new S3Filesystem($client, $config['bucket'], $config['prefix'] ?? '', $config['options'] ?? []);
    }

    /**
     * Returns the configuration associated with a given backup type.
     *
     * @return array<mixed>
     */
    protected function getConfig(string $name): array
    {
        return config("backups.disks.$name") ?: [];
    }

    /**
     * Get the default backup driver name.
     */
    public function getDefaultAdapter(): string
    {
        return config('backups.default');
    }

    /**
     * Set the default session driver name.
     */
    public function setDefaultAdapter(string $name): void
    {
        config()->set('backups.default', $name);
    }

    /**
     * Unset the given adapter instances.
     *
     * @param  string|string[]  $adapter
     */
    public function forget(array|string $adapter): self
    {
        $adapters = &$this->adapters;
        foreach ((array) $adapter as $adapterName) {
            unset($adapters[$adapterName]);
        }

        return $this;
    }

    /**
     * Register a custom adapter creator closure.
     */
    public function extend(string $adapter, Closure $callback): self
    {
        $this->customCreators[$adapter] = $callback;

        return $this;
    }
}
