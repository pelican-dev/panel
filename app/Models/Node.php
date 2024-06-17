<?php

namespace App\Models;

use App\Exceptions\Service\HasActiveServersException;
use App\Repositories\Daemon\DaemonConfigurationRepository;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @property int $id
 * @property string $uuid
 * @property bool $public
 * @property string $name
 * @property string|null $description
 * @property string $fqdn
 * @property string $scheme
 * @property bool $behind_proxy
 * @property bool $maintenance_mode
 * @property int $memory
 * @property int $memory_overallocate
 * @property int $disk
 * @property int $disk_overallocate
 * @property int $cpu
 * @property int $cpu_overallocate
 * @property int $upload_size
 * @property string $daemon_token_id
 * @property string $daemon_token
 * @property int $daemon_listen
 * @property int $daemon_sftp
 * @property string|null $daemon_sftp_alias
 * @property string $daemon_base
 * @property array $tags
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \App\Models\Mount[]|\Illuminate\Database\Eloquent\Collection $mounts
 * @property \App\Models\Server[]|\Illuminate\Database\Eloquent\Collection $servers
 * @property \App\Models\Allocation[]|\Illuminate\Database\Eloquent\Collection $allocations
 */
class Node extends Model
{
    use Notifiable;

    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal.
     */
    public const RESOURCE_NAME = 'node';

    public const DAEMON_TOKEN_ID_LENGTH = 16;
    public const DAEMON_TOKEN_LENGTH = 64;

    /**
     * The table associated with the model.
     */
    protected $table = 'nodes';

    /**
     * The attributes excluded from the model's JSON form.
     */
    protected $hidden = ['daemon_token_id', 'daemon_token'];

    /**
     * Fields that are mass assignable.
     */
    protected $fillable = [
        'public', 'name',
        'fqdn', 'scheme', 'behind_proxy',
        'memory', 'memory_overallocate', 'disk',
        'disk_overallocate', 'cpu', 'cpu_overallocate',
        'upload_size', 'daemon_base',
        'daemon_sftp', 'daemon_sftp_alias', 'daemon_listen',
        'description', 'maintenance_mode',
    ];

    public static array $validationRules = [
        'name' => 'required|regex:/^([\w .-]{1,100})$/',
        'description' => 'string|nullable',
        'public' => 'boolean',
        'fqdn' => 'required|string',
        'scheme' => 'required|string|in:http,https',
        'behind_proxy' => 'boolean',
        'memory' => 'required|numeric|min:0',
        'memory_overallocate' => 'required|numeric|min:-1',
        'disk' => 'required|numeric|min:0',
        'disk_overallocate' => 'required|numeric|min:-1',
        'cpu' => 'required|numeric|min:0',
        'cpu_overallocate' => 'required|numeric|min:-1',
        'daemon_base' => 'sometimes|required|regex:/^([\/][\d\w.\-\/]+)$/',
        'daemon_sftp' => 'required|numeric|between:1,65535',
        'daemon_sftp_alias' => 'nullable|string',
        'daemon_listen' => 'required|numeric|between:1,65535',
        'maintenance_mode' => 'boolean',
        'upload_size' => 'int|between:1,1024',
    ];

    /**
     * Default values for specific columns that are generally not changed on base installs.
     */
    protected $attributes = [
        'public' => true,
        'behind_proxy' => false,
        'memory' => 0,
        'memory_overallocate' => 0,
        'disk' => 0,
        'disk_overallocate' => 0,
        'cpu' => 0,
        'cpu_overallocate' => 0,
        'daemon_base' => '/var/lib/pelican/volumes',
        'daemon_sftp' => 2022,
        'daemon_listen' => 8080,
        'maintenance_mode' => false,
        'tags' => '[]',
    ];

    protected function casts(): array
    {
        return [
            'memory' => 'integer',
            'disk' => 'integer',
            'cpu' => 'integer',
            'daemon_listen' => 'integer',
            'daemon_sftp' => 'integer',
            'daemon_token' => 'encrypted',
            'behind_proxy' => 'boolean',
            'public' => 'boolean',
            'maintenance_mode' => 'boolean',
            'tags' => 'array',
        ];
    }

    public int $servers_sum_memory = 0;
    public int $servers_sum_disk = 0;
    public int $servers_sum_cpu = 0;

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    protected static function booted(): void
    {
        static::creating(function (self $node) {
            $node->uuid = Str::uuid();
            $node->daemon_token = Str::random(self::DAEMON_TOKEN_LENGTH);
            $node->daemon_token_id = Str::random(self::DAEMON_TOKEN_ID_LENGTH);

            return true;
        });

        static::deleting(function (self $node) {
            throw_if($node->servers()->count(), new HasActiveServersException(trans('exceptions.egg.delete_has_servers')));
        });
    }

    /**
     * Get the connection address to use when making calls to this node.
     */
    public function getConnectionAddress(): string
    {
        return "$this->scheme://$this->fqdn:$this->daemon_listen";
    }

    /**
     * Returns the configuration as an array.
     */
    public function getConfiguration(): array
    {
        return [
            'debug' => false,
            'uuid' => $this->uuid,
            'token_id' => $this->daemon_token_id,
            'token' => $this->daemon_token,
            'api' => [
                'host' => '0.0.0.0',
                'port' => $this->daemon_listen,
                'ssl' => [
                    'enabled' => (!$this->behind_proxy && $this->scheme === 'https'),
                    'cert' => '/etc/letsencrypt/live/' . Str::lower($this->fqdn) . '/fullchain.pem',
                    'key' => '/etc/letsencrypt/live/' . Str::lower($this->fqdn) . '/privkey.pem',
                ],
                'upload_limit' => $this->upload_size,
            ],
            'system' => [
                'data' => $this->daemon_base,
                'sftp' => [
                    'bind_port' => $this->daemon_sftp,
                ],
            ],
            'allowed_mounts' => $this->mounts->pluck('source')->toArray(),
            'remote' => route('index'),
        ];
    }

    /**
     * Returns the configuration in Yaml format.
     */
    public function getYamlConfiguration(): string
    {
        return Yaml::dump($this->getConfiguration(), 4, 2, Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE);
    }

    /**
     * Returns the configuration in JSON format.
     */
    public function getJsonConfiguration(bool $pretty = false): string
    {
        return json_encode($this->getConfiguration(), $pretty ? JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT : JSON_UNESCAPED_SLASHES);
    }

    public function isUnderMaintenance(): bool
    {
        return $this->maintenance_mode;
    }

    public function mounts(): HasManyThrough
    {
        return $this->hasManyThrough(Mount::class, MountNode::class, 'node_id', 'id', 'id', 'mount_id');
    }

    /**
     * Gets the servers associated with a node.
     */
    public function servers(): HasMany
    {
        return $this->hasMany(Server::class);
    }

    /**
     * Gets the allocations associated with a node.
     */
    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class);
    }

    /**
     * Returns a boolean if the node is viable for an additional server to be placed on it.
     */
    public function isViable(int $memory, int $disk, int $cpu): bool
    {
        if ($this->memory_overallocate >= 0) {
            $memoryLimit = $this->memory * (1 + ($this->memory_overallocate / 100));
            if ($this->servers_sum_memory + $memory > $memoryLimit) {
                return false;
            }
        }

        if ($this->disk_overallocate >= 0) {
            $diskLimit = $this->disk * (1 + ($this->disk_overallocate / 100));
            if ($this->servers_sum_disk + $disk > $diskLimit) {
                return false;
            }
        }

        if ($this->cpu_overallocate >= 0) {
            $cpuLimit = $this->cpu * (1 + ($this->cpu_overallocate / 100));
            if ($this->servers_sum_cpu + $cpu > $cpuLimit) {
                return false;
            }
        }

        return true;
    }

    public static function getForServerCreation()
    {
        return self::with('allocations')->get()->map(function (Node $item) {
            $filtered = $item->getRelation('allocations')->where('server_id', null)->map(function ($map) {
                return collect($map)->only(['id', 'ip', 'port']);
            });

            $ports = $filtered->map(function ($map) {
                return [
                    'id' => $map['id'],
                    'text' => sprintf('%s:%s', $map['ip'], $map['port']),
                ];
            })->values();

            return [
                'id' => $item->id,
                'text' => $item->name,
                'allocations' => $ports,
            ];
        })->values();
    }

    public function systemInformation(): array
    {
        return once(function () {
            try {
                return resolve(DaemonConfigurationRepository::class)
                    ->setNode($this)
                    ->getSystemInformation(connectTimeout: 3);
            } catch (Exception $exception) {
                $message = str($exception->getMessage());

                if ($message->startsWith('cURL error 6: Could not resolve host')) {
                    $message = str('Could not resolve host');
                }

                if ($message->startsWith('cURL error 28: Failed to connect to ')) {
                    $message = $message->after('cURL error 28: ')->before(' after ');
                }

                return ['exception' => $message->toString()];
            }
        });
    }

    public function serverStatuses(): array
    {
        $statuses = [];
        try {
            $statuses = Http::daemon($this)->connectTimeout(1)->timeout(1)->get('/api/servers')->json() ?? [];
        } catch (Exception $exception) {
            report($exception);
        }

        foreach ($statuses as $status) {
            $uuid = fluent($status)->get('configuration.uuid');
            cache()->remember("servers.$uuid.container.status", now()->addMinute(), fn () => fluent($status)->get('state'));
        }

        return $statuses;
    }

    public function statistics()
    {
        $default = [
            'memory_total' => 0,
            'memory_used' => 0,
            'swap_total' => 0,
            'swap_used' => 0,
            'load_average1' => 0.00,
            'load_average5' => 0.00,
            'load_average15' => 0.00,
            'cpu_percent' => 0.00,
            'disk_total' => 0,
            'disk_used' => 0,
        ];

        try {
            return Http::daemon($this)
                ->connectTimeout(1)
                ->timeout(1)
                ->get('/api/system/utilization')
                ->json() ?? $default;
        } catch (Exception) {
            return $default;
        }
    }

    public function ipAddresses(): array
    {
        return cache()->remember("nodes.$this->id.ips", now()->addHour(), function () {
            $ips = collect();
            if (is_ip($this->fqdn)) {
                $ips = $ips->push($this->fqdn);
            } elseif ($dnsRecords = gethostbynamel($this->fqdn)) {
                $ips = $ips->concat($dnsRecords);
            }

            try {
                $addresses = Http::daemon($this)->connectTimeout(1)->timeout(1)->get('/api/system/ips')->json();
                $ips = $ips->concat(fluent($addresses)->get('ip_addresses'));
            } catch (Exception) {
                // pass
            }

            return $ips->all();
        });
    }
}
